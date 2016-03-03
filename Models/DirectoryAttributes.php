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

abstract class DirectoryAttributes
{
    const NAME        = 'name';
    const DN          = 'dn';
    const OU          = 'ou';
    const USERNAME    = 'username';
    const CN          = 'cn';
    const FIRSTNAME   = 'firstname';
    const LASTNAME    = 'lastname';
    const DISPLAYNAME = 'displayname';
    const EMAIL       = 'email';
    const TITLE       = 'title';
    const DEPARTMENT  = 'department';
    const DIVISION    = 'division';
    const LOCATION    = 'location';
    const ADDRESS     = 'address';
    const CITY        = 'city';
    const STATE       = 'state';
    const ZIP         = 'zip';
    const OFFICE      = 'office';
    const FAX         = 'fax';
    const CELL        = 'cell';
    const OTHER       = 'other';
    const PAGER       = 'pager';
    const EMPLOYEENUM = 'employeeNum';
    const EMPLOYEEID  = 'employeeID';

    /**
     * Maps inernal application fieldnames to LDAP attributes
     *
     * Each key is the internal name.
     * Each value is the LDAP attribute name
     */
    public static $fields = [
        self::NAME        => 'name',
        self::DN          => 'distinguishedname',
        self::OU          => 'ou',
        self::USERNAME    => 'samaccountname',
        self::CN          => 'cn',
        self::FIRSTNAME   => 'givenname',
        self::LASTNAME    => 'sn',
        self::DISPLAYNAME => 'displayname',
        self::EMAIL       => 'mail',
        self::TITLE       => 'title',
        self::DEPARTMENT  => 'department',
        self::DIVISION    => 'division',
        self::LOCATION    => 'physicaldeliveryofficename',
        self::ADDRESS     => 'street',
        self::CITY        => 'l',
        self::STATE       => 'st',
        self::ZIP         => 'postalcode',
        self::OFFICE      => 'telephonenumber',
        self::FAX         => 'facsimiletelephonenumber',
        self::CELL        => 'mobile',
        self::OTHER       => 'othertelephone',
        self::PAGER       => 'pager',
        self::EMPLOYEENUM => 'employeeNumber',
        self::EMPLOYEEID  => 'employeeID'
    ];

    public static $phoneNumberFields = [
        self::OFFICE, self::FAX, self::CELL, self::OTHER, self::PAGER
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