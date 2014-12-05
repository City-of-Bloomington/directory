<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Models;

use Blossom\Classes\Ldap;

class DepartmentGateway
{
    private $config;
    private $connection;
    private static $hiddenDepartments = ['Other Users'];

    public function __construct()
    {
        global $DIRECTORY_CONFIG;

        $this->config = $DIRECTORY_CONFIG['Employee'];
        $this->connection = Ldap::getConnection($this->config);

        if (!$this->connection) {
            print_r($this->config);
            throw new \Exception('ldap/failedConnection');
        }
    }

    public function getDepartments($dn='')
    {
        if (!$dn) { $dn = 'OU=Departments,'.$this->config['DIRECTORY_BASE_DN']; }

        $departments = [];

        $result = ldap_list($this->connection, $dn, "(objectClass=organizationalUnit)");
        $count = ldap_count_entries($this->connection, $result);
        if ($count) {
            ldap_sort($this->connection, $result, 'name');
            $entries = ldap_get_entries($this->connection, $result);
            for ($i=0; $i<$count; $i++) {
                if (!in_array($entries[$i]['name'][0], self::$hiddenDepartments)) {
                    $departments[] = new Department($entries[$i], $this);
                }
            }
        }
        return $departments;
    }

    public function getDepartment($dn)
    {
        $result = ldap_read($this->connection, $dn, "objectClass=organizationalUnit");
        if (   ldap_count_entries($this->connection, $result)) {
            $e = ldap_get_entries($this->connection, $result);
            return new Department($e[0], $this);
        }
        else {
            throw new \Exception('ldap/unknownDepartment');
        }

    }

    /**
     * @return array An array of Person objects
     */
    public function search($fields)
    {
        # Build the LDAP query
        if (!empty($fields['firstname']) || !empty($fields['lastname'])) {
            if (!empty($fields['firstname'])) { $f[] = "(|(givenName=$fields[firstname]*)(displayName=$fields[firstname]*))"; }
            if (!empty($fields['lastname' ])) { $f[] = "(|(sn=$fields[lastname]*)(sn=*-$fields[lastname]*))"; }

            $filter = (count($f) > 1)
                ? '(&'.implode('', $f).')'
                : $f[0];
        }
        elseif (!empty($fields['extension'])) {
            $filter = "(telephoneNumber=*$fields[extension])";
        }
        elseif (!empty($fields['query'])) {
            $q = $fields['query'];
            $filter = "(|(givenName=$q*)(displayName=$q*)(sn=$q*)(mail=$q*)(cn=$q*))";
        }

        $dn = 'OU=Departments,'.$this->config['DIRECTORY_BASE_DN'];
        $result = ldap_search($this->connection, $dn, $filter);

        return $this->hydratePersonObjects($result);
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
    public function getPeople($dn=null)
    {
        if ($dn) {
            $result = ldap_list($this->connection, $dn, "objectClass=user");
        }
        else {
            $dn = 'OU=Departments,'.$this->config['DIRECTORY_BASE_DN'];
            $result = ldap_search($this->connection, $dn, "objectClass=user");
        }
        return $this->hydratePersonObjects($result);
    }

    /**
     * @return array An array of Person objects
     */
    private function hydratePersonObjects($result)
    {
        $people = [];
        $count = ldap_count_entries($this->connection, $result);
        if ($count) {
            ldap_sort($this->connection, $result, 'sn');
            $entries = ldap_get_entries($this->connection, $result);
            for ($i=0; $i<$count; $i++) {
                // Ignore user account flagged with an asterisk
                if (strpos($entries[$i]['cn'][0], '*') === false) {
                    $people[] = new Person($entries[$i], $this);
                }
            }
        }
        return $people;
    }

    public function getPerson($username)
    {
        $result = ldap_search(
            $this->connection,
            'OU=Departments,'.$this->config['DIRECTORY_BASE_DN'],
            "(&(objectClass=person)(cn=$username))"
        );
        $count = ldap_count_entries($this->connection, $result);
        if ($count) {
            $entries = ldap_get_entries($this->connection, $result);
            return new Person($entries[0], $this);
        }
    }

    public function getPhoto($username)
    {
        $result = ldap_search(
            $this->connection,
            'OU=Departments,'.$this->config['DIRECTORY_BASE_DN'],
            "(&(objectClass=person)(cn=$username))",
            ['jpegphoto']
        );
        $count = ldap_count_entries($this->connection, $result);
        if ($count) {
            $e = ldap_first_entry($this->connection, $result);
            $attributes = ldap_get_attributes($this->connection, $e);
            if (!empty($attributes['jpegPhoto'][0])) {
                $data = ldap_get_values_len($this->connection, $e, 'jpegphoto');
                return $data[0];
            }
        }
    }

    public function getTelephoneNumbers($dn='')
    {
        $departments = [];

        if (!$dn) { $dn = 'OU=Departments,'.$this->config['DIRECTORY_BASE_DN']; }

        $result = ldap_search(
            $this->connection, $dn, '(&(objectClass=organizationalUnit)(telephoneNumber=*))'
        );
        $count = ldap_count_entries($this->connection, $result);
        if ($count) {
            ldap_sort($this->connection, $result, 'name');
            $entries = ldap_get_entries($this->connection, $result);
            for ($i=0; $i<$count; $i++) {
                $departments[] = new Department($entries[$i], $this);
            }
        }
        return $departments;
    }

}