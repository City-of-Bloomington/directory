<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Departments\Actions\Info;

use Domain\Departments\DataStorage\DepartmentsGateway;

class Command
{
    private $gw;
    public function __construct(DepartmentsGateway $gateway)
    {
        $this->gw = $gateway;
    }

    public function __invoke(Request $req): Response
    {
        try {
            $dn          = $this->gw->dnForPath($req->path);
            $filters     = $req->promoted ? ['promoted'=>true] : [];
            $departments = $this->gw->getDepartments($dn, $filters);
            return new Response($departments[0]);
        }
        catch (\Exception $e) {
            return new Response(null, [$e->getMessage()]);
        }
    }
}
