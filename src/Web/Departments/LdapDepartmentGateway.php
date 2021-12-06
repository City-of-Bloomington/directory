<?php
/**
 * @copyright 2014-2021 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Web\Departments;

use Web\Ldap;
use Web\LdapEntry;
use Web\View;
use Domain\Departments\DataStorage\DepartmentsGateway;
use Domain\Departments\Entities\Department;
use Domain\People\Actions\Update\Request as UpdateRequest;
use Domain\People\Entities\Person;

class LdapDepartmentGateway extends Ldap implements DepartmentsGateway
{
    const LDAP_CONNECTION_FAILED = 'ldap/failedConnection';

    private static $hiddenDepartments = ['Departments','Other Users'];
    private static $department_dn;

    public function base_dn(): string { return $this->getConfig()['base_dn']; }

    private function hydrate(array $entry): Department
    {
        $department        = new Department();
        $publishableFields = LdapEntry::getPublishableFields(LdapEntry::TYPE_DEPARTMENT);

        foreach ((array)$department as $k=>$v) {
            if (array_key_exists($k, $publishableFields)
                && !empty($entry[$publishableFields[$k]])) {

                $department->$k = $entry[$publishableFields[$k]][0];
            }
        }
        $department->path = $this->pathForDn($department->dn);
        return $department;
    }

    private static function hydratePerson(array $entry): Person
    {
        $person = new Person();
        $publishableFields = LdapEntry::getPublishableFields(LdapEntry::TYPE_PERSON);

        foreach ((array)$person as $k=>$v) {
            if (array_key_exists($k, $publishableFields)
                && !empty($entry[$publishableFields[$k]])) {

                $person->$k = $entry[$publishableFields[$k]][0];
            }
        }
        if (file_exists(SITE_HOME."/photos/{$person->username}.jpg")) {
            $person->photoUri = View::generateUri('people.photo', ['username'=>$person->username]);
        }
        return $person;
    }

    /**
     * @return array An array of Department objects
     */
    public function getDepartments(?string $dn=null, ?array $personFilters=[]): array
    {
        if (!$dn) { $dn = $this->base_dn(); }

        $departments = [];
        $fields      = array_values(LdapEntry::getPublishableFields(LdapEntry::TYPE_DEPARTMENT));
        $result      = ldap_search($this->connection, $dn, "(objectClass=organizationalUnit)", $fields);
        $count       = ldap_count_entries($this->connection, $result);
        if ($count) {
            $entries = ldap_get_entries($this->connection, $result);
            unset($entries['count']);

            foreach ($entries as $entry) {
                if (!in_array($entry['name'][0], self::$hiddenDepartments)) {
                    $departments[] = $this->hydrate($entry);
                }
            }
            usort($departments, function ($a, $b) {
                if (   $a->path === $b->path) { return 0; }
                return $a->path  <  $b->path ? -1 : 1;
            });

            # Because the departments are now sorted by DN, we can match
            # against the innermost DN by working through the list
            # in reverse order
            $c = count($departments);

            // Put people inside their departments
            $people = $this->search($dn, $personFilters);
            foreach ($people as $p) {
                for ($i=$c-1; $i>=0; $i--) {
                    if (strpos($p->dn, $departments[$i]->dn) !== false) {
                        $departments[$i]->people[] = $p;
                        break;
                    }
                }
            }

            // Nest children inside their parents
            for ($i=$c-1; $i>0; $i--) {
                for ($j=$i-1; $j>=0; $j--) {
                    if (strpos($departments[$i]->path, $departments[$j]->path) !== false) {
                        $departments[$j]->children[] = $departments[$i];
                        unset($departments[$i]);
                        break;
                    }
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
     * Returns an objectClass filter based on internal/external access
     *
     * We want to make sure that we honor the DIRECTORY_RESTRICTED config.
     * If external traffic is requesting information, we need to make sure
     * we're only including people results that are members of the this group.
     *
     * For internal traffic, we can include all people results.
     */
    public function getPersonFilter(): string
    {
        $config = $this->getConfig();

        return View::isAllowed('people', 'restricted')
            ? "(&(objectClass=person)(!(memberof=$config[restricted])))"
            : '(objectClass=person)';
    }

    /**
     * Search for People records
     *
     * @param  string $base_dn  Base DN for the query
     * @param  array  $fields   An array of key=>values to search on
     * @return array            An array of Person objects
     */
    public function search(string $base_dn, array $fields): array
    {
        # Build the LDAP query
        $filters  = !empty($fields['query'])
                  ? $this->simpleSearchFilters  ($fields['query'])
                  : $this->advancedSearchFilters($fields);

        // If there's more than one filter, combine them with AND
        $filter   = (count($filters) > 1)
                  ? '(&'.implode('', $filters).')'
                  : $filters[0];

        $result   = ldap_search($this->connection,
                                $base_dn,
                                $filter,
                                array_values(LdapEntry::getPublishableFields(LdapEntry::TYPE_PERSON)));

        $people = [];
        $count = ldap_count_entries($this->connection, $result);
        if ($count) {
            $entries = ldap_get_entries($this->connection, $result);
            for ($i=0; $i<$count; $i++) {
                // Ignore user account flagged with an asterisk
                if (strpos($entries[$i]['cn'][0], '*') === false) {
                    $people[] = self::hydratePerson($entries[$i]);
                }
            }
        }
        usort($people, function ($a, $b) {
            $al = $a->lastname ?? '';
            $bl = $b->lastname ?? '';

            if     ($al === $bl) { return 0; }
            return ($al  <  $bl) ? -1 : 1;
        });
        return $people;
    }

    /**
     * @param  string $query  Text string to search for
     * @return array          An array of ldap filter strings
     */
    private function simpleSearchFilters(string $query): array
    {
        $filters = [$this->getPersonFilter()];
        $search  = ["(sAMAccountName=$query*)",
                       "(displayName=$query*)",
                         "(givenName=$query*)",
                                "(sn=$query*)"];
        if (View::isAllowed('people', 'phones')) {
            $search[] = "(telephoneNumber=*$query*)";
        }
        $filters[] = '(|'.implode('', $search).')';
        return $filters;
    }

    /**
     * @param  array  $fields   An array of key=>values to search on
     * @return array            An array of ldap filter strings
     */
    private function advancedSearchFilters(array $fields): array
    {
        $f      = [$this->getPersonFilter()];
        $config =  self::getConfig();

        foreach ($fields as $key=>$value) {
            switch ($key) {
                case LdapEntry::FIRSTNAME:
                    $f[] = "(|(givenName=$value*)(displayName=$value*))";
                break;

                case LdapEntry::LASTNAME:
                    $f[] = "(|(sn=$value*)(sn=*-$value*))";
                break;

                case LdapEntry::EMPLOYEENUM:
                    $f[] = empty($value)
                         ? "(!(employeeNumber=*))"
                         :   "(employeeNumber=$value)";
                break;

                case LdapEntry::PROMOTED:
                    $group = $config[LdapEntry::PROMOTED];
                    $f[]   = $value
                           ?   "(memberOf=$group)"
                           : "(!(memberOf=$group))";
                break;

                case LdapEntry::EXTENSION:
                    if (View::isAllowed('people', 'phones')) {
                        $f[] = "(telephoneNumber=*$value)";
                    }
                break;

                case LdapEntry::NON_PAYROLL:
                    if (View::isAllowed('people', 'nonpayroll')) {
                        $group = $config[LdapEntry::NON_PAYROLL];
                        $f[]   = empty($value)
                                ? "(!(memberOf=$group))"
                                :   "(memberOf=$group)";
                    }
                break;

                default:
                    $ldap = LdapEntry::getPublishableFields();
                    if (array_key_exists($key, $ldap)) {
                        $f[] = "({$ldap[$key]}=$value)";
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
     * @throws Exception
     * @param  string $id Username or Employee Number
     * @return Person
     */
    public function getPerson(string $id): Person
    {
        $objectClass = $this->getPersonFilter();
        $filter = is_numeric($id)
            ? "employeeNumber=$id"
            : "sAMAccountName=$id";

        $result = ldap_search(
            $this->connection,
            $this->base_dn(),
            "(&$objectClass($filter))",
            array_values(LdapEntry::getPublishableFields(LdapEntry::TYPE_PERSON))
        );
        $count = ldap_count_entries($this->connection, $result);
        if ($count) {
            $entries = ldap_get_entries($this->connection, $result);
            return self::hydratePerson($entries[0]);
        }
        throw new \Exception('people/unknown');
    }

    /**
     * @param Person $person
     * @param string $file      Full path to image file
     */
    public function savePhoto(Person $person, string $file)
    {
        clearstatcache();
        $size = filesize($file);

        $newFile   = SITE_HOME."/photos/{$person->username}.jpg";
        $directory = dirname($newFile);
        if (!is_dir($directory)) {
            mkdir  ($directory, 0776, true);
        }
        move_uploaded_file($file, $newFile);

        // Check and make sure the file was saved
        clearstatcache();
        $ns = filesize($newFile);
        if (!is_file($newFile) || filesize($newFile)!=$size) {
            throw new \Exception('media/badServerPermissions');
        }
    }

    /**
     * Saves a person back to Ldap
     */
    public function update(UpdateRequest $req): Person
    {
        $person   = $this->getPerson($req->username);
        $data     = (array)$req;
        $modified = [];
        $deleted  = [];
        $ldap     = LdapEntry::getPublishableFields(LdapEntry::TYPE_PERSON);

        unset($data['username']);
        if (!View::isAllowed('people', 'updateHr')) { unset($data['employeeid']); }

        foreach ($data as $k=>$v) {
            if ($person->$k != $v) {
                if ($v) { $modified[$ldap[$k]] = $v; }
                else    {  $deleted[$ldap[$k]] = []; }
            }
        }

        if ($modified) {
            if (!ldap_mod_replace($this->connection, $person->dn, $modified)) { throw new \Exception(ldap_error($this->connection)); }
        }
        if ($deleted ) {
            if (!ldap_mod_del    ($this->connection, $person->dn, $deleted )) { throw new \Exception(ldap_error($this->connection)); }
        }

        return $this->getPerson($req->username);
    }

    /**
     * Creates a DN string for a given path
     *
     * This function only prepares a well-formed string
     * using the given path.  It may not actually be a DN
     * that exists in the directory.
     */
    public function dnForPath(string $path): string
    {
        if ($path[0] === '/') { $path = substr($path, 1); }

        $dn = $this->base_dn();

        $matches = explode('/', $path);
        foreach ($matches as $ou) {
            $ou = str_replace('_', ' ', $ou);
            $dn = "OU=$ou,$dn";
        }
        return $dn;
    }

    public static function pathForDn(string $dn): string
    {
        $path = '';
        preg_match_all("|OU=([^,]+),|", $dn, $matches);
        if (count($matches[1]) > 1) {
            // Matches will also include the OU=Departments,
            // which we don't want to be part of the path.
            array_pop($matches[1]);

            foreach ($matches[1] as $name) {
                $name = str_replace(' ', '_', strtolower($name));
                $path = "/$name$path";
            }
        }
        return $path;
    }
}
