<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Models;
use Blossom\Classes\ActiveRecord;
use Blossom\Classes\Database;
use Zend\Db\Sql\Sql;

class EmergencyContact extends ActiveRecord
{
    protected $tablename = 'emergencyContacts';

    private $person;

    /**
     * Maps contact fields to the names used for Everbridge
     * The $key is the internal fieldname in the database
     * The #value is the name of the field for Everbridge.
     */
    public static $contactFields = [
        'email_1' => 'Email Address 1',
        'email_2' => 'Email Address 2',
        'email_3' => 'Email Address 3',
        'sms_1'   => 'SMS 1',
        'sms_2'   => 'SMS 2',
        'phone_1' => 'Phone 1',
        'phone_2' => 'Phone 2',
        'phone_3' => 'Phone 3',
        'tty_1'   => 'TTY 1'
    ];

    /**
     * Populates the object with data
     *
     * Passing in an associative array of data will populate this object without
     * hitting the database.
     *
     * Passing in a scalar will load the data from the database.
     * This will load all fields in the table as properties of this class.
     * You may want to replace this with, or add your own extra, custom loading
     *
     * @param Mixed $id (ID, email, username, Person)
     */
    public function __construct($id=null)
    {
        if ($id) {
            if (is_array($id)) {
                $this->exchangeArray($id);
            }
            else {
                $zend_db = Database::getConnection();
                if (ActiveRecord::isId($id)) {
                    $sql = 'select * from emergencyContacts where id=?';
                }
                else {
                    $sql = 'select * from emergencyContacts where username=?';
                }

                if ($id instanceof Person) {
                    $this->person = $id;
                    $id = $id->getUsername();
                }

                $result = $zend_db->createStatement($sql)->execute([$id]);
                if (count($result)) {
                    $this->exchangeArray($result->current());
                }
                else {
                    #throw new \Exception('person/unknownEmergencyContact');
                    // Create a new, empty contact object for this person
                    if (!ActiveRecord::isId($id)) {
                        $this->setUsername($id);
                    }
                }
            }
        }
        else {
            // This is where the code goes to generate a new, empty instance.
            // Set any default values for properties that need it here
        }
    }

    public function validate()
    {
        if (!$this->getUsername()) {
            throw new \Exception('missingRequiredFields');
        }

        foreach (array_keys(self::$contactFields) as $f) {
            $get     = 'get'.ucfirst($f);
            $type    = substr($f, 0, 1)=='e' ? 'Email' : 'Phone';
            $isValid = "isValid$type";

            if ($this->$get() && !$this->$isValid($this->$get())) {
                throw new \Exception("emergencyContacts/invalid$type");
            }
        }
    }

    public function save() { parent::save(); }

    //----------------------------------------------------------------
    // Generic Getters & Setters
    //----------------------------------------------------------------
    public function getId()       { return parent::get('id'      ); }
    public function getUsername() { return parent::get('username'); }
    public function getEmail_1()  { return parent::get('email_1' ); }
    public function getEmail_2()  { return parent::get('email_2' ); }
    public function getEmail_3()  { return parent::get('email_3' ); }
    public function getSms_1  ()  { return parent::get('sms_1'   ); }
    public function getSms_2  ()  { return parent::get('sms_2'   ); }
    public function getPhone_1()  { return parent::get('phone_1' ); }
    public function getPhone_2()  { return parent::get('phone_2' ); }
    public function getPhone_3()  { return parent::get('phone_3' ); }
    public function getTty_1  ()  { return parent::get('tty_1'   ); }
    public function getEmployeeId () { return parent::get('employeeId' ); }
    public function getEmployeeNum() { return parent::get('employeeNum'); }
    public function getDepartment () { return parent::get('department' ); }
    public function getWorkSite   () { return parent::get('workSite'   ); }

    public function setUsername($s) { parent::set('username', $s); }
    public function setEmail_1 ($s) { parent::set('email_1',  $s); }
    public function setEmail_2 ($s) { parent::set('email_2',  $s); }
    public function setEmail_3 ($s) { parent::set('email_3',  $s); }
    public function setSms_1   ($s) { parent::set('sms_1',    $this->cleanPhone($s)); }
    public function setSms_2   ($s) { parent::set('sms_2',    $this->cleanPhone($s)); }
    public function setPhone_1 ($s) { parent::set('phone_1',  $this->cleanPhone($s)); }
    public function setPhone_2 ($s) { parent::set('phone_2',  $this->cleanPhone($s)); }
    public function setPhone_3 ($s) { parent::set('phone_3',  $this->cleanPhone($s)); }
    public function setTty_1   ($s) { parent::set('tty_1',    $this->cleanPhone($s)); }
    public function setEmployeeId ($i) { parent::set('employeeId',  $i); }
    public function setEmployeeNum($i) { parent::set('employeeNum', $i); }
    public function setDepartment ($s) { parent::set('department',  $s); }
    public function setWorkSite   ($s) { parent::set('workSite',    $s); }

    public function handleUpdate($post)
    {
        foreach (array_keys(self::$contactFields) as $f) {
            $set = 'set'.ucfirst($f);
            $this->$set($post[$f]);
        }
    }

    //----------------------------------------------------------------
    // Custom functions
    //----------------------------------------------------------------
    public function isValidEmail($string)
    {
        $regex = "|^[a-zA-Z0-9.!#$%&'*+/=?^_`{\|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$|";
        return preg_match($regex, $string) ? true : false;
    }

    public function isValidPhone($string)
    {
        return preg_match('|^\d{10}$|', $string) ? true : false;
    }

    /**
     * @return int
     */
    public function cleanPhone($string)
    {
        return preg_replace('/[^0-9]/', '', $string);
    }

    /**
     * @return Person
     */
    public function getPerson(DepartmentGateway $gateway)
    {
        if (!$this->person) {
            $this->person = $gateway->getPerson($this->getUsername());
        }
        return $this->person;
    }
}