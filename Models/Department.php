<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Models;

class Department
{
    use DirectoryAttributes;

    private $gateway;
    private $children = [];

    public function __construct($ldap_entry, DepartmentGateway $gateway)
    {
        $this->gateway = $gateway;
        $this->entry   = $ldap_entry;
    }

    public function getChildren()
    {
        if (!$this->children) {
            $departments = $this->gateway->getDepartments($this->dn);
            if (count($departments)) {
                foreach ($departments as $d) {
                    $this->children[] = $d;
                }
            }
        }
        return $this->children;
    }

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
            return $this->gateway->getPeople($this->dn);
        }
    }

    /**
     * Returns the breadcrumb path to this department
     *
     * @return array[name => dn]
     */
    public function getPath()
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