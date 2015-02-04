<?php
/**
 * Wrapper class for an LDAP entry
 *
 * @copyright 2014-2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Models;

use Blossom\Classes\Database;

class Person
{
    use DirectoryAttributes;

    private $gateway;

    /**
     * Whitelist of accepted file types
     */
    public static $validPhotoFormats = ['jpg'];

    public function __construct($ldap_entry, DepartmentGateway $gateway)
    {
        $this->gateway = $gateway;
        $this->entry   = $ldap_entry;
    }

    public function handleUpdate($post)
    {
        foreach (self::$phoneNumberFields as $field) {
            $this->$field = $post[$field];
        }
    }

    public function getFullname()
    {
        return $this->displayname
            ?  $this->displayname
            : "{$this->firstname} {$this->lastname}";
    }

    /**
     * @override
     */
    public function getAddress()
    {
        $address = $this->address;
        return $address ? $address : $this->getDepartmentObject()->address;
    }

    /**
     * @param array|string Either a $_FILES array or a path to a file
     */
    public function setPhoto($file)
    {
        $tempFile = is_array($file) ? $file['tmp_name'] : $file;
        $filename = is_array($file) ? basename($file['name']) : basename($file);
        if (!$tempFile) {
            throw new \Exception('media/uploadFailed');
        }
        clearstatcache();
        $size = filesize($tempFile);

        // Find out the mime type for this file
        $extension = self::getExtension($filename);
        if (!in_array($extension, self::$validPhotoFormats)) {
            throw new \Exception('media/unknownFileType');
        }

        // Move the file where it's supposed to go
        $newFile   = APPLICATION_HOME."/data/photos/{$this->username}.jpg";
        $directory = dirname($newFile);
        if (!is_dir($directory)) {
            mkdir  ($directory, 0777, true);
        }
        move_uploaded_file($tempFile, $newFile);

        // Check and make sure the file was saved
        clearstatcache();
        $ns = filesize($newFile);
        echo "size: $size|ns: $ns\n";
        if (!is_file($newFile) || filesize($newFile)!=$size) {
            throw new \Exception('media/badServerPermissions');
        }
    }

    public function hasPhoto()
    {
        return file_exists(APPLICATION_HOME."/public/photos/{$this->username}.jpg");
    }

    /**
     * @return string
     */
    public function getPhotoUrl() { return BASE_URL."/photos/{$this->username}.jpg"; }
    public function getPhotoUri() { return BASE_URI."/photos/{$this->username}.jpg"; }

    /**
     * Returns whether the Ldap entry has a photo for this person
     *
     * @return boolean
     */
    public function hasLdapPhoto()
    {
        return !empty($this->entry['jpegphoto']);
    }

    /**
     * Returns the raw Ldap photo data
     *
     * @return string
     */
    public function getLdapPhoto()
    {
        return $this->entry['jpegphoto'][0];
    }

    /**
     * @return string
     */
    private static function getExtension($filename)
    {
        if (preg_match("/[^.]+$/", $filename, $matches)) {
            return strtolower($matches[0]);
        }
        else {
            echo "$filename has no extension\n";
            throw new \Exception('media/missingExtension');
        }
    }

    /**
     * @return Department
     */
    public function getDepartmentObject()
    {
        if (preg_match('/CN=[^,]+,(.+$)/', $this->dn, $matches)) {
            return $this->gateway->getDepartment($matches[1]);
        }
    }

    /**
     * @return EmergencyContact
     */
    public function getEmergencyContacts()
    {
        return new EmergencyContact($this);
    }
}