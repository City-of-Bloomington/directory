<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\People\Actions\Update;

class Request
{
    public $username;
    
    public $address;
    public $city;
    public $state;
    public $zip;
    
    public $office;
    public $fax;
    public $cell;
    public $other;
    public $pager;
    
    public $employeeid;
    
    public function __construct(array $data=null)
    {  
        if ($data) {
            foreach ($this as $k=>$v) {
                if (!empty($data[$k])) { $this->$k = $data[$k]; }
            }
        }
    }
}
