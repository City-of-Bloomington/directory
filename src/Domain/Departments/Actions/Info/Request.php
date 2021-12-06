<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Departments\Actions\Info;

class Request
{
    public $path;        // uri style path to department
    public $promoted;    // Whether to fllter people to only those promoted

    public function __construct(string $path, ?bool $promoted=false)
    {
        $this->path     = $path;
        $this->promoted = $promoted;
    }
}
