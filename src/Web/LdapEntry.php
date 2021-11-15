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
 * @copyright 2014-2021 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Web;

abstract class LdapEntry
{
    public    $entry    = [];
    protected $deleted  = [];
    protected $modified = [];

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

    const EXTENSION   = 'extension';
    const NON_PAYROLL = 'non-payroll';
    const PROMOTED    = 'promoted';

    /**
     * @see https://www.php.net/manual/en/function.ldap-get-entries.php
     *
     * @param array $entry A single LDAP entry
     */
    public function __construct(array $entry)
    {
        $this->entry = $entry;
    }

    /**
     * Maps internal application fieldnames to LDAP attributes
     * Includes only the fields that are allowed to be published
     *
     * Each key is the internal name.
     * Each value is the LDAP attribute name
     *
     * We are publishing different sets of information about people,
     * depending on whether the request is being made internally or
     * externally.
     *
     * Even if a person's record is okay to publish to the outside world,
     * there are still fields that we want to keep hidden.
     */
    public static function getPublishableFields()
    {
        // Fields that are freely available to the outside world
        $f = [
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
            self::FAX         => 'facsimiletelephonenumber',
            self::PAGER       => 'pager'
        ];

        // These are fields of information we want to only publish internally
        if (get_called_class() === __namespace__.'\Department'
            || View::isAllowed('people', 'phones')) {

            $f[self::OFFICE] = 'telephonenumber';
            $f[self::CELL  ] = 'mobile';
            $f[self::OTHER ] = 'othertelephone';
        }

        if (View::isAllowed('people', 'hr')) {
            $f[self::EMPLOYEENUM] = 'employeenumber';
            $f[self::EMPLOYEEID ] = 'employeeid';
        }

        return $f;
    }

    public static function getEditableFields(): array
    {
        $fields = array_merge(
            [self::ADDRESS, self::CITY, self::STATE, self::ZIP],
            self::$phoneNumberFields
        );

        if (Person::isAllowed('hr', 'edit')) {
            $fields[] = self::EMPLOYEEID;
        }
        return $fields;
    }

    public static $phoneNumberFields = [
        self::OFFICE, self::FAX, self::CELL, self::OTHER, self::PAGER
    ];

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
        $publishable = self::getPublishableFields();

        if (array_key_exists($field, $publishable)) {
            $attribute = $publishable[$field];
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
        $publishable = self::getPublishableFields();

        if (array_key_exists($field, $publishable)) {
            $currentValue = $this->$field;
            $attribute    = $publishable[$field];

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

    public function getPhone()
    {
        if (!DepartmentGateway::isExternalRequest()) {
            foreach (self::$phoneNumberFields as $field) {
                $v = $this->__get($field);
                if ($v) { return $v; }
            }
        }
        else {
            return $this->__get(self::PAGER);
        }
    }
}
