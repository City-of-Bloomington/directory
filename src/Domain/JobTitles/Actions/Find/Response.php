<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\JobTitles\Actions\Find;

class Response
{
    public $titles = [];
    public $errors = [];

    public function __construct(?array $titles=null, ?array $errors=null)
    {
        $this->titles = $titles;
        $this->errors = $errors;
    }
}
