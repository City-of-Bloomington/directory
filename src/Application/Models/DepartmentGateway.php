<?php
/**
 * @copyright 2014-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

use Blossom\Classes\ActiveRecord;

class DepartmentGateway
{
    const LDAP_CONNECTION_FAILED = 'ldap/failedConnection';

    private static $config;
    private static $connection;
    private static $hiddenDepartments = ['Other Users'];
    private static $department_dn;

    /**
     * Returns the config array
     *
     * The array is created in /data/site_config.inc
     *
     * @return array
     */
    public static function getConfig()
    {
        global $DIRECTORY_CONFIG;
        if (!self::$config) {
             self::$config = $DIRECTORY_CONFIG['Employee'];
        }
        return self::$config;
    }

    /**
     * Returns the LDAP connection
     *
     * @return resource
     */
    public static function getConnection()
    {
        if (!self::$connection) {
             self::$connection = Ldap::getConnection(self::getConfig());

            if (!self::$connection) {
                throw new \Exception(self::LDAP_CONNECTION_FAILED);
            }
        }
        return self::$connection;
    }

    /**
     * @return string
     */
    private static function getDepartmentDn()
    {
        $c = self::getConfig();
        return $c['DIRECTORY_BASE_DN'];
    }

    /**
     * @param string $dn
     * @return array An array of Department objects
     */
    public static function getDepartments($dn='')
    {
        $ldap = self::getConnection();

        if (!$dn) { $dn = self::getDepartmentDn(); }

        $departments = [];

        $result  = ldap_list($ldap, $dn, "(objectClass=organizationalUnit)", array_values(Department::getPublishableFields()));
        $count   = ldap_count_entries($ldap, $result);
        if ($count) {
            $entries = ldap_get_entries($ldap, $result);
            unset($entries['count']);

            usort($entries, function ($a, $b) {
                if (   $a['name'][0] === $b['name'][0]) { return 0; }
                return $a['name'][0]  <  $b['name'][0] ? -1 : 1;
            });

            foreach ($entries as $entry) {
                if (!in_array($entry['name'][0], self::$hiddenDepartments)) {
                    $departments[] = new Department($entry);
                }
            }
        }
        return $departments;
    }

    /**
     * @param string $dn
     * @return Department
     */
    public static function getDepartment($dn)
    {
        $ldap = self::getConnection();

        $result = @ldap_read(
            $ldap,
            $dn,
            "(objectClass=organizationalUnit)",
            array_values(Department::getPublishableFields())
        );
        if ($result
            && ldap_count_entries($ldap, $result)) {
            $e = ldap_get_entries($ldap, $result);
            return new Department($e[0]);
        }
        else {
            throw new \Exception('ldap/unknownDepartment');
        }
    }

    /**
     * Returns whether the request is from outside or not
     *
     * We want to make sure that we honor the DIRECTORY_RESTRICTED config.
     * If external traffic is requesting information, we need to make sure
     * we're only including people results that are members of the this group.
     *
     * @return boolean
     */
    public static function isExternalRequest()
    {
        $config = self::getConfig();

        // @NOTE
        // When running on the command line, we need to decide what IP address to use
        // Right now, this will just use 127.0.0.1, which might not be what we want
        $ipAddress = isset($_SERVER['HTTP_X_FORWARDED_FOR'])
                         ? $_SERVER['HTTP_X_FORWARDED_FOR']
                         : (isset($_SERVER['REMOTE_ADDR'])
                                ? $_SERVER['REMOTE_ADDR']
                                : gethostbyname(gethostname()));

        return ($config['DIRECTORY_RESTRICTED']
                && !preg_match("/$config[DIRECTORY_INTERNAL_IP]/", $ipAddress));
    }

    /**
     * Returns an objectClass filter based on internal/external access
     *
     * We want to make sure that we honor the DIRECTORY_RESTRICTED config.
     * If external traffic is requesting information, we need to make sure
     * we're only including people results that are members of the this group.
     *
     * For internal traffic, we can include all people results.
     *
     * @return string
     */
    public static function getPersonFilter(): string
    {
        $config = self::getConfig();

        return self::isExternalRequest()
            ? "(&(objectClass=person)(!(memberof=$config[DIRECTORY_RESTRICTED])))"
            : '(objectClass=person)';
    }

    /**
     * Search for People records
     *
     * @param  string $base_dn  Base DN for the query
     * @param  array  $fields   An array of key=>values to search on
     * @return array            An array of Person objects
     */
    public static function search(string $base_dn, array $fields): array
    {
        # Build the LDAP query
        $filters  = !empty($fields['query'])
                  ? self::simpleSearchFilters  ($fields['query'])
                  : self::advancedSearchFilters($fields);

        // If there's more than one filter, combine them with AND
        $filter   = (count($filters) > 1)
                  ? '(&'.implode('', $filters).')'
                  : $filters[0];

        $ldap     = self::getConnection();
        $result   = ldap_search($ldap,
                                $base_dn,
                                $filter,
                                array_values(Person::getPublishableFields()));

        return self::hydratePersonObjects($result);
    }

    /**
     * @param  string $query  Text string to search for
     * @return array          An array of ldap filter strings
     */
    private static function simpleSearchFilters(string $query): array
    {
        $filters = [self::getPersonFilter()];
        $search  = ["(sAMAccountName=$query*)",
                       "(displayName=$query*)",
                         "(givenName=$query*)",
                                "(sn=$query*)"];
        if (!self::isExternalRequest()) {
            $search[] = "(telephoneNumber=*$query*)";
        }
        $filters[] = '(|'.implode('', $search).')';
        return $filters;
    }

    /**
     * @param  array  $fields   An array of key=>values to search on
     * @return array            An array of ldap filter strings
     */
    private static function advancedSearchFilters(array $fields): array
    {
        $f      = [self::getPersonFilter()];
        $config =  self::getConfig();

        foreach ($fields as $key=>$value) {
            switch ($key) {
                case DirectoryAttributes::FIRSTNAME:
                    $f[] = "(|(givenName=$value*)(displayName=$value*))";
                break;

                case DirectoryAttributes::LASTNAME:
                    $f[] = "(|(sn=$value*)(sn=*-$value*))";
                break;

                case DirectoryAttributes::EMPLOYEENUM:
                    $f[] = empty($value)
                         ? "(!(employeeNumber=*))"
                         :   "(employeeNumber=$value)";
                break;

                case DirectoryAttributes::PROMOTED:
                    $f[] = $value
                         ?   "(memberOf=$config[DIRECTORY_PROMOTED])"
                         : "(!(memberOf=$config[DIRECTORY_PROMOTED]))";
                break;

                default:
                    if (array_key_exists($key, Person::getPublishableFields())) {
                        $ldapKey = DirectoryAttributes::$fields[$key];
                        $f[]     = "($ldapKey=$value)";
                    }
            }

            if (!self::isExternalRequest()) {
                switch ($key) {
                    case DirectoryAttributes::EXTENSION:
                        $f[] = "(telephoneNumber=*$value)";
                    break;

                    case DirectoryAttributes::NON_PAYROLL:
                        $f[] = empty($value)
                             ? "(!(memberOf=$config[DIRECTORY_NONPAYROLL]))"
                             :   "(memberOf=$config[DIRECTORY_NONPAYROLL])";
                    break;
                }
            }
        }
        return $f;
    }

    /**
     * Returns person objects from the Directory
     *
     * If you provide a dn, this only returns people in that dn
     * If you do not provide a dn, then ALL users are retuned
     *
     * @param  string $dn
     * @return array       An array of Person objects
     */
    public static function getPeople(string $dn)
    {
        return self::search($dn, []);
    }

    /**
     * @param resource LDAP Result Set
     * @return array An array of Person objects
     */
    private static function hydratePersonObjects($result)
    {
        $ldap = self::getConnection();

        $people = [];
        $count = ldap_count_entries($ldap, $result);
        if ($count) {
            $entries = ldap_get_entries($ldap, $result);
            for ($i=0; $i<$count; $i++) {
                // Ignore user account flagged with an asterisk
                if (strpos($entries[$i]['cn'][0], '*') === false) {
                    $people[] = new Person($entries[$i]);
                }
            }
        }
        usort($people, function ($a, $b) {
            $al = isset($a->entry['sn'][0]) ? $a->entry['sn'][0] : '';
            $bl = isset($b->entry['sn'][0]) ? $b->entry['sn'][0] : '';

            if     ($al === $bl) { return 0; }
            return ($al  <  $bl) ? -1 : 1;
        });
        return $people;
    }

    /**
     * @param string $id Username or Employee Number
     * @return Person
     */
    public static function getPerson($id)
    {
        $objectClass = self::getPersonFilter();
        $filter = ActiveRecord::isId($id)
            ? "employeeNumber=$id"
            : "sAMAccountName=$id";

        $ldap = self::getConnection();
        $result = ldap_search(
            $ldap,
            self::getDepartmentDn(),
            "(&$objectClass($filter))",
            array_values(Person::getPublishableFields())
        );
        $count = ldap_count_entries($ldap, $result);
        if ($count) {
            $entries = ldap_get_entries($ldap, $result);
            return new Person($entries[0]);
        }
    }

    /**
     * Returns the binary data for the image stored in LDAP
     *
     * @param  string $username
     * @return data              Binary image data
     */
    public static function getPhoto($username)
    {
        $objectClass = self::getPersonFilter();

        $ldap = self::getConnection();
        $result = ldap_search(
            $ldap,
            self::getDepartmentDn(),
            "(&$objectClass(sAMAccountName=$username))",
            ['jpegphoto']
        );
        $count = ldap_count_entries($ldap, $result);
        if ($count) {
            $e = ldap_first_entry($ldap, $result);
            $attributes = ldap_get_attributes($ldap, $e);
            if (!empty($attributes['jpegPhoto'][0])) {
                $data = ldap_get_values_len($ldap, $e, 'jpegphoto');
                return $data[0];
            }
        }
    }

    /**
     * @param string $dn
     * @param array $modified
     * @param array $deleted
     */
    public static function update($dn, array $modified=null, array $deleted=null)
    {
        $ldap = self::getConnection();
        if ($modified) { ldap_mod_replace($ldap, $dn, $modified); }
        if ($deleted ) { ldap_mod_del    ($ldap, $dn, $deleted ); }
    }

    /**
     * Creates a DN string for a given path
     *
     * This function only prepares a well-formed string
     * using the given path.  It may not actually be a DN
     * that exists in the directory.
     *
     * @param string $path
     * @return string
     */
    public static function getDnForPath($path)
    {
        if ($path[0] === '/') { $path = substr($path, 1); }

        $dn = self::getDepartmentDn();

        $matches = explode('/', $path);
        foreach ($matches as $ou) {
            $ou = str_replace('_', ' ', $ou);
            $dn = "OU=$ou,$dn";
        }
        return $dn;
    }

    /**
     * @param string $dn
     * @return string
     */
    public static function getPathForDn($dn)
    {
        preg_match_all("|OU=([^,]+),|", $dn, $matches);
        if (count($matches[1]) > 1) {
            // Matches will also include the OU=Departments,
            // which we don't want to be part of the path.
            array_pop($matches[1]);

            $path = '';
            foreach ($matches[1] as $name) {
                $name = str_replace(' ', '_', strtolower($name));
                $path = "/$name$path";
            }
            return $path;
        }
    }
}
