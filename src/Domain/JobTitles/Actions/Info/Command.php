<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\JobTitles\Actions\Info;

use Domain\JobTitles\DataStorage\JobTitlesRepository;

class Command
{
    private $repo;

    public function __construct(JobTitlesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(int $id): Response
    {
        try {
            $title = $this->repo->load($id);
            return new Response($title);
        }
        catch (\Exception $e) {
            return new Response(null, [$e->getMessage()]);
        }
    }
}
