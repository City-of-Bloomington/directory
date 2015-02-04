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

    public function getPeople()
    {
        return $this->gateway->getPeople($this->dn);
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