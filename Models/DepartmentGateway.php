<?php
/**
 * @copyright 2014-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

use Blossom\Classes\ActiveRecord;

class DepartmentGateway
{
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
                throw new \Exception('ldap/failedConnection');
            }
        }
        return self::$connection;
    }

    /**
     * @return string
     */
    public static function getDepartmentDn()
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

        $result  = ldap_list($ldap, $dn, "(objectClass=organizationalUnit)", array_values(DirectoryAttributes::getPublishableFields()));
        $count = ldap_count_entries($ldap, $result);
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
            array_values(DirectoryAttributes::getPublishableFields())
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
     * We want to make sure that we honor the DIRECTORY_PUBLIC_GROUP config.
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
        $ipAddress = isset($_SERVER['REMOTE_ADDR'])
                        ?  $_SERVER['REMOTE_ADDR']
                        :  gethostbyname(gethostname());

        return ($config['DIRECTORY_PUBLIC_GROUP']
                && !preg_match("/$config[DIRECTORY_INTERNAL_IP]/", $ipAddress));
    }

    /**
     * Returns an objectClass filter based on internal/external access
     *
     * We want to make sure that we honor the DIRECTORY_PUBLIC_GROUP config.
     * If external traffic is requesting information, we need to make sure
     * we're only including people results that are members of the this group.
     *
     * For internal traffic, we can include all people results.
     *
     * @return string
     */
    public static function getPersonFilter()
    {
        $config = self::getConfig();

        return self::isExternalRequest()
            ? "(&(objectClass=person)(memberof=$config[DIRECTORY_PUBLIC_GROUP]))"
            : '(objectClass=person)';
    }

    /**
     * @param array $fields An array of key=>values to search on
     * @return array An array of Person objects
     */
    public static function search($fields)
    {
        # Build the LDAP query
        $f = [self::getPersonFilter()];
        if (!empty($fields['query'])) {
            $q = $fields['query'];
            $f[] = "(|(givenName=$q*)(displayName=$q*)(sn=$q*)(mail=$q*)(sAMAccountName=$q*))";
        }
        else {
            $publishable = DirectoryAttributes::getPublishableFields();
            foreach ($fields as $key=>$value) {
                if (array_key_exists($key, $publishable)) {
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

                        default:
                            $ldapFieldname = DirectoryAttributes::$fields[$key];
                            $f[] = "($ldapFieldname=$value)";
                    }
                }
                elseif (!self::isExternalRequest()) {
                    switch ($key) {
                        case 'extension':
                            $f[] = "(telephoneNumber=*$value)";
                        break;

                        case 'non-payroll':
                            $config = self::getConfig();
                            $f[] = empty($value)
                                ? "(!(memberOf=$config[DIRECTORY_NONPAYROLL]))"
                                :   "(memberOf=$config[DIRECTORY_NONPAYROLL])";
                        break;
                    }
                }
            }
        }
        $filter = (count($f) > 1)
            ? '(&'.implode('', $f).')'
            : $f[0];

        $ldap = self::getConnection();
        $result = ldap_search(
            $ldap,
            self::getDepartmentDn(),
            $filter,
            array_values(DirectoryAttributes::getPublishableFields())
        );

        return self::hydratePersonObjects($result);
    }

    /**
     * Returns person objects from the Directory
     *
     * If you provide a dn, this only returns people in that dn
     * If you do not provide a dn, then ALL users are retuned
     *
     * @param string $dn
     * @return array An array of Person objects
     */
    public static function getPeople($dn=null)
    {
        if (!$dn) { $dn = self::getDepartmentDn(); }

        $ldap = self::getConnection();
        $result = ldap_search(
            $ldap,
            $dn,
            self::getPersonFilter(),
            array_values(DirectoryAttributes::getPublishableFields())
        );
        return self::hydratePersonObjects($result);
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
            if     ($a->entry['sn'][0] === $b->entry['sn'][0]) { return 0; }
            return ($a->entry['sn'][0]  <  $b->entry['sn'][0]) ? -1 : 1;
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
            array_values(DirectoryAttributes::getPublishableFields())
        );
        $count = ldap_count_entries($ldap, $result);
        if ($count) {
            $entries = ldap_get_entries($ldap, $result);
            return new Person($entries[0]);
        }
    }

    /**
     * @param string $username
     * @return data Binary image data
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