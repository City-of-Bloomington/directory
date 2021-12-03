<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\People\Actions\Search;

class Response
{
    public $people = [];

    public function __construct(?array $people=[])
    {
        $this->people = $people;
    }
}
