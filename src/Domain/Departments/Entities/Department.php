<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Departments\Entities;

class Department
{
    public $name;
    public $dn;
    public $ou;
    public $cn;
    public $displayname;
    public $email;
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

    public $path;
    public $children = [];
    public $people   = [];
}
