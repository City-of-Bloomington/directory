<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\People\Entities;

class Person
{
    public $name;
    public $dn;
    public $ou;
    public $cn;
    public $username;
    public $firstname;
    public $lastname;
    public $displayname;
    public $email;
    public $title;
    public $location;
    public $address;
    public $city;
    public $state;
    public $zip;
    public $office;
    public $fax;
    public $cell;
    public $other;
    public $pager;
    public $extension;

    public $department;
    public $division;
    public $employeenumber;
    public $employeeid;

    public function fullname(): string
    {
        return $this->displayname ?? "{$this->firstname} {$this->lastname}";
    }

    public function phone(): ?string
    {
        foreach (['office', 'cell', 'other', 'pager'] as $p) {
            if ($this->$p) { return $this->$p; }
        }
        return null;
    }
}
