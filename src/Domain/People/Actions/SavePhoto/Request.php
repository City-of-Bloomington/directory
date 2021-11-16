<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\People\Actions\SavePhoto;

class Request
{
    public $username;
    public $file;

    public function __construct(string $username, string $file)
    {
        $this->username = $username;
        $this->file     = $file;
    }
}
