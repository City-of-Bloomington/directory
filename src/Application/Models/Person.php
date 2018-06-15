<?php
/**
 * Wrapper class for an LDAP entry
 *
 * @copyright 2014-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

use Blossom\Classes\Database;

class Person extends DirectoryAttributes
{
    private $photoFile;
    /**
     * Whitelist of accepted file types
     */
    public static $validPhotoFormats = ['jpg'];

    public function __construct($ldap_entry)
    {
        $this->entry     = $ldap_entry;
        $this->photoFile = "/photos/{$this->username}.jpg";
    }

    public function handleUpdate($post)
    {
        $fields  = [self::ADDRESS, self::CITY, self::STATE, self::ZIP];
        foreach ($fields as $f) {
            $this->$f = $post[$f];
        }

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
        $newFile   = SITE_HOME.$this->photoFile;
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

    public function hasPhoto     (): bool { return $this->hasLocalPhoto() || $this->hasLdapPhoto(); }
    public function hasLdapPhoto (): bool { return DepartmentGateway::getPhoto($this->username) ? true : false; }
    public function hasLocalPhoto(): bool { return $this->photoFile && file_exists(SITE_HOME.$this->photoFile); }
    public function getPhotoUrl(): string { return BASE_URL.$this->photoFile; }
    public function getPhotoUri(): string { return BASE_URI.$this->photoFile; }
    public function getPhoto() {
        return $this->hasLocalPhoto()
            ? file_get_contents(SITE_HOME.$this->photoFile)
            : DepartmentGateway::getPhoto($this->username);
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
        $dn = explode(',', $this->entry['dn']);
        return DepartmentGateway::getDepartment($dn[1]);
    }

    /**
     * @return EmergencyContact
     */
    public function getEmergencyContacts()
    {
        return new EmergencyContact($this);
    }

    /**
     * Returns all data about this person
     *
     * The data returned should be ready for encoding into JSON or XML
     *
     * @return array
     */
    public function getData()
    {
        $out = [];

        foreach (array_keys(self::getPublishableFields()) as $f) {
            $out[$f] = $this->$f;
        }
        if ($this->hasPhoto()) { $out['photo'] = $this->getPhotoUrl(); }

        return $out;
    }
}
