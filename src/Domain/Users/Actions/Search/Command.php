<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Users\Actions\Search;

use Domain\Users\DataStorage\UsersRepository;
use Domain\Users\Entities\User;

class Command
{
    private $repo;

    public function __construct(UsersRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(Request $req): Response
    {
        try {
            $result = $this->repo->search($req);
            return new Response($result['rows'], $result['total']);
        }
        catch (\Exception $e) {
            return new Response([], null, [$e->getMessage()]);
        }
    }
}
