<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\People\Actions\Update;

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
        $response = new Response();
        try {
            $person = $this->gw->update($req);
            $response->person = $person;
            return $response;
        }
        catch (\Exception $e) {
            $response->errors = [$e->getMessage()];
            
            try {
                $person = $this->gw->getPerson($req->username);
                $response->person = $person;
            }
            catch (Exception $e) { }
        }
        
        return $response;
    }
}
