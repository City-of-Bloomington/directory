<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\People\Actions\Search;

use Domain\Departments\DataStorage\DepartmentsGateway;

class Command
{
    private $gw;
    public function __construct(DepartmentsGateway $gateway)
    {
        $this->gw = $gateway;
    }

    public function __invoke(string $query): Response
    {
        $people = $this->gw->search($this->gw->base_dn(), ['query'=>$query]);
        return new Response($people);
    }
}
