<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
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
        $this->gateway    = $gateway;
        $this->ldap_entry = $ldap_entry;
    }

    public function getFullname()
    {
        return $this->get('displayname')
            ?  $this->get('displayname')
            : "{$this->getFirstname()} {$this->getLastname()}";
    }

    /**
     * @override
     */
    public function getAddress()
    {
        $address = $this->get('street');
        return $address ? $address : $this->getDepartmentObject()->getAddress();
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
        $size = filesize($tempFile);

        // Find out the mime type for this file
        $extension = self::getExtension($filename);
        if (!in_array($extension, self::$validPhotoFormats)) {
            throw new \Exception('media/unknownFileType');
        }

        // Move the file where it's supposed to go
        $newFile   = APPLICATION_HOME."/data/photos/{$this->getUsername()}.jpg";
        $directory = dirname($newFile);
        if (!is_dir($directory)) {
            mkdir  ($directory, 0777, true);
        }
        move_uploaded_file($tempFile, $newFile);
        chmod($newFile, 0666);

        // Check and make sure the file was saved
        if (!is_file($newFile) || filesize($newFile)!=$size) {
            throw new \Exception('media/badServerPermissions');
        }
    }

    public function hasPhoto()
    {
        return file_exists(APPLICATION_HOME."/public/photos/{$this->getUsername()}.jpg");
    }

    /**
     * @return string
     */
    public function getPhotoUrl() { return BASE_URL."/photos/{$this->getUsername()}.jpg"; }
    public function getPhotoUri() { return BASE_URI."/photos/{$this->getUsername()}.jpg"; }

    /**
     * Returns whether the Ldap entry has a photo for this person
     *
     * @return boolean
     */
    public function hasLdapPhoto()
    {
        return !empty($this->ldap_entry['jpegphoto']);
    }

    /**
     * Returns the raw Ldap photo data
     *
     * @return string
     */
    public function getLdapPhoto()
    {
        return $this->ldap_entry['jpegphoto'][0];
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
        if (preg_match('/CN=[^,]+,(.+$)/', $this->getDn(), $matches)) {
            return $this->gateway->getDepartment($matches[1]);
        }
    }

    /**
     * @return EmergencyContact
     */
    public function getEmergencyContacts()
    {
        return new EmergencyContact($this->getUsername());
    }
}