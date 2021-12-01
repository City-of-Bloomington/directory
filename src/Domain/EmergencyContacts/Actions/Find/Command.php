<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\EmergencyContacts\Actions\Find;

use Domain\EmergencyContacts\Repository;

class Command
{
    private $repo;

    public function __construct(Repository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(): Response
    {
        try {
            $contacts = $this->repo->find();
            return new Response($contacts);
        }
        catch (\Exception $e) {
            return new Response(null, [$e->getMessage()]);
        }
    }
}
