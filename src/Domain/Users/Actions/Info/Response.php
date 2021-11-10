<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Users\Actions\Info;

use Domain\Users\Entities\User;

class Response
{
    public $user;
    public $errors = [];

    public function __construct(?User $user, ?array $errors=null)
    {
        if ($user  ) { $this->user   = $user;   }
        if ($errors) { $this->errors = $errors; }
    }
}
