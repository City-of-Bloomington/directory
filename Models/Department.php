<?php
/**
 * @copyright 2014-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

class Department extends DirectoryAttributes
{

    private $children = [];

    public function __construct($ldap_entry)
    {
        $this->entry   = $ldap_entry;
    }

    /**
     * @return string
     */
    public function getUrl() { return BASE_URL.DepartmentGateway::getPathForDn($this->entry['dn']); }
    public function getUri() { return BASE_URI.DepartmentGateway::getPathForDn($this->entry['dn']); }

    /**
     * @return array
     */
    public function getChildren()
    {
        if (!$this->children) {
            $departments = DepartmentGateway::getDepartments($this->dn);
            if (count($departments)) {
                foreach ($departments as $d) {
                    $this->children[] = $d;
                }
            }
        }
        return $this->children;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return count($this->getChildren()) ? true : false;
    }


    /**
     * Returns people in this department
     *
     * If $people is passed in, $people is used as the data source, instead of LDAP.
     *
     * So, if you've already queried LDAP for a set of people, you can
     * filter your existing result set to this department.  Just pass your existing
     * result set to this function.
     *
     * @param array $people An array of Person objects
     * @return array An array of Person objects
     */
    public function getPeople(&$people=null)
    {
        $c = count($people);
        if (count($people)) {
            $out = [];
            if ($this->entry['ou'][0] != 'Departments') {
                foreach ($people as $person) {
                    if ($person->entry['dn'] === "CN={$person->entry['cn'][0]},{$this->entry['dn']}") {
                        $out[] = $person;
                    }
                }
            }
            return $out;
        }
        else {
            return DepartmentGateway::getPeople($this->dn);
        }
    }

    /**
     * Returns the breadcrumb path to this department
     *
     * @return array[name => dn]
     */
    public function getBreadcrumbs()
    {
        $breadcrumbs = [];

        $dn = $this->dn;
        while (preg_match('/OU=([^,]+),(.+$)/', $dn, $matches)) {
            $breadcrumbs[$matches[1]] = $matches[0];
            $dn = $matches[2];
        }
        return array_reverse($breadcrumbs);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return DepartmentGateway::getPathForDn($this->entry['dn']);
    }

    /**
     * Returns all data for this department
     *
     * The data returned should be ready for encoding into JSON or XML
     *
     * @return array
     */
    public function getData(&$staff=null)
    {
        if (!$staff) {
            # Grab all the people inside this department, including sub-departments
            $staff = $this->getPeople();
        }

        $out = [];
        foreach (array_keys(self::$fields) as $f) { $out[$f] = $this->$f; }
        $out['path']   = $this->getPath();


        # Are there any people in just this department?
        $people = $this->getPeople($staff);
        if (count($people)) {
            $out['people'] = [];
            foreach ($people as $p) {
                $out['people'][] = $p->getData();
            }
        }

        $children = $this->getChildren();
        if (count($children)) {
            foreach ($children as $d) {
                $out['departments'][] = $d->getData();
            }
        }
        return $out;
   }
}