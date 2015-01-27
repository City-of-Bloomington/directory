<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Models;

trait DirectoryAttributes
{
    protected $ldap_entry;

    /**
     * Returns the first scalar value from the entry's field
     *
     * @param string $field
     * @return string
     */
    public function get($field) {
        return !empty($this->ldap_entry[$field][0]) ? $this->ldap_entry[$field][0] : '';
    }

    public function getName()       { return $this->get('name'); }
    public function getDn()         { return $this->get('distinguishedname'); }

    public function getUsername()   { return $this->get('samaccountname');         }
    public function getFirstname()  { return $this->get('givenname');  }
    public function getLastname()   { return $this->get('sn');         }
    public function getEmail()      { return $this->get('mail');       }
    public function getTitle()      { return $this->get('title');      }
    public function getDepartment() { return $this->get('department'); }
    public function getLocation()   { return $this->get('physicaldeliveryofficename'); }
    public function getAddress()    { return $this->get('street');     }
    public function getCity()       { return $this->get('l');          }
    public function getState()      { return $this->get('state');      }
    public function getZip()        { return $this->get('postalcode'); }


    public function getPhones()
    {
        $phones = [];
        $fields = [
            'office' => 'telephonenumber',
            'fax'    => 'facsimiletelephonenumber',
            'cell'   => 'mobile',
            'other'  => 'othertelephone',
            'pager'  => 'pager'
        ];
        foreach ($fields as $label=>$f) {
            if (   isset($this->ldap_entry[$f])) {
                foreach ($this->ldap_entry[$f] as $value) {
                    $phones[$label] = $value;
                }
            }
        }
        return $phones;
    }
}