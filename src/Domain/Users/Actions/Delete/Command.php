<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Users\Actions\Delete;

use Domain\Users\DataStorage\UsersRepository;

class Command
{
    private $repo;

    public function __construct(UsersRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(int $id): Response
    {
        try {
            $this->repo->delete($id);
        }
        catch (\Exception $e) {
            return new Response([$e->getMessage()]);
        }
        return new Response();
    }
}
