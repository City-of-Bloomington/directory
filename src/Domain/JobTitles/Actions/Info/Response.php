<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\JobTitles\Actions\Info;

use Domain\JobTitles\Entities\JobTitle;

class Response
{
    public $title;
    public $errors = [];

    public function __construct(?JobTitle $title=null, ?array $errors=null)
    {
        $this->title  = $title;
        $this->errors = $errors;
    }
}
