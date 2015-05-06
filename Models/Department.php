<?php
/**
 * @copyright 2014-2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Models;

class Department
{
    use DirectoryAttributes;

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
                    $d = explode(',', $person->entry['dn']);
                    $dn = substr($d[1], 3);

                    $ou = $this->entry['ou'][0];
                    if ($dn == $ou) { $out[] = $person; }
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
}