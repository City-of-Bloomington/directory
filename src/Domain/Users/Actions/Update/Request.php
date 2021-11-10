<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Users\Actions\Update;

class Request
{
    public $id;
    public $firstname;
    public $lastname;
    public $email;

    public $username;
    public $role;
    public $password;
    public $authentication_method;

    public function __construct(?array $data=null)
    {
        if ($data) {
            if (!empty($data['id'       ])) { $this->id        = (int)$data['id'  ]; }
            if (!empty($data['firstname'])) { $this->firstname = $data['firstname']; }
            if (!empty($data['lastname' ])) { $this->lastname  = $data['lastname' ]; }
            if (!empty($data['email'    ])) { $this->email     = $data['email'    ]; }

            if (!empty($data['username' ])) { $this->username  = $data['username' ]; }
            if (!empty($data['password' ])) { $this->password  = $data['password' ]; }
            if (!empty($data['role'     ])) { $this->role      = $data['role'     ]; }
            if (!empty($data['authentication_method'])) { $this->authentication_method = $data['authentication_method']; }
        }
    }
}
