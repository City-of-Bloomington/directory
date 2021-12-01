<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\EmergencyContacts\Actions\Load;

use Domain\EmergencyContacts\Contact;
use Domain\EmergencyContacts\Repository;

class Command
{
    private $repo;

    public function __construct(Repository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(string $username): Response
    {
        try {
            return new Response($this->repo->load($username));
        }
        catch (\Exception $e) {
            return new Response(null, [$e->getMessage()]);
        }
    }
}
