<?php
/**
 * Maps attribute names between LDAP and Directory
 *
 * In Directory, we have a set of Person properties that
 * we store in LDAP/ActiveDirectory.  Which LDAP attributes
 * are used changes over time.  This class translates the
 * names used in Directory with the names of the LDAP
 * attributes where they are stored.
 *
 * For example, Directory uses "username", which can be stored
 * in LDAP as CN or UID; also in ActiveDirectory it could be stored
 * as the sAMAccountName.  This class declares the mapping for
 * all the fields used in the Directory application.
 *
 * @copyright 2014-2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Models;

trait DirectoryAttributes
{
    /**
     * Maps inernal application fieldnames to LDAP attributes
     *
     * Each key is the internal name.
     * Each value is the LDAP attribute name
     */
    public static $fields = [
        'name'        => 'name',
        'dn'          => 'distinguishedname',
        'ou'          => 'ou',
        'username'    => 'samaccountname',
        'cn'          => 'cn',
        'firstname'   => 'givenname',
        'lastname'    => 'sn',
        'displayname' => 'displayname',
        'email'       => 'mail',
        'title'       => 'title',
        'department'  => 'department',
        'division'    => 'division',
        'location'    => 'physicaldeliveryofficename',
        'address'     => 'street',
        'city'        => 'l',
        'state'       => 'state',
        'zip'         => 'postalcode',
        'office'      => 'telephonenumber',
        'fax'         => 'facsimiletelephonenumber',
        'cell'        => 'mobile',
        'other'       => 'othertelephone',
        'pager'       => 'pager'
    ];

    public static $phoneNumberFields = [
        'office', 'fax', 'cell', 'other', 'pager'
    ];

    /**
     * The raw LDAP entry
     */
    public $entry;

    protected $deleted  = [];
    protected $modified = [];

    public function save()
    {
        if ($this->modified || $this->deleted) {
            DepartmentGateway::update($this->dn, $this->modified, $this->deleted);
        }
    }

    /**
     * Returns the first scalar value from the entry's field
     *
     * The $field parameter is the internal name for the field,
     * not the LDAP attribute name, which can change over time
     * as we move things around.
     *
     * @param string $field The internal name for the field
     * @return string
     */
    public function __get($field) {
        if (array_key_exists($field, self::$fields)) {
            $attribute = self::$fields[$field];
            if (!empty($this->entry[$attribute])) {
                if ($this->entry[$attribute]['count'] == 1) {
                    return $this->entry[$attribute][0];
                }
                else {
                    $e = $this->entry[$attribute];
                    unset($e['count']);
                    return $e;
                }
            }
        }
        else {
            throw new \Exception("unknownAttribute: $field");
        }
        return '';
    }

    /**
     * @param string $field The internal name for the field
     * @param mixed $value The new value to set
     */
    public function __set($field, $value)
    {
        if (array_key_exists($field, self::$fields)) {
            $currentValue = $this->$field;
            $attribute    = self::$fields[$field];

            if ($value) {
                if ($value != $currentValue) {
                    $this->modified[$attribute] = $value;
                }

                if (!is_array($value)) {
                    $value = [0=>$value, 'count'=>1];
                }
                else {
                    $value['count'] = count($value);
                }
                $this->entry[$attribute] = $value;
            }
            else {
                if ($currentValue) {
                    $this->entry  [$attribute] = [];
                    $this->deleted[$attribute] = [];
                }
            }
        }
        else {
            throw new \Exception('unknownAttribute');
        }
    }

    public function getMainPhone()
    {
        foreach (self::$phoneNumberFields as $field) {
            $v = $this->__get($field);
            if ($v) { return $v; }
        }
    }
}