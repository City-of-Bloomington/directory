<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\JobTitles\Actions\Update;

use Domain\JobTitles\DataStorage\JobTitlesRepository;
use Domain\JobTitles\Entities\JobTitle;

class Command
{
    private $repo;

    public function __construct(JobTitlesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(Request $req): Response
    {
        $errors = $this->validate($req);
        if ($errors) { return new Response($req->id, $errors); }

        try {
            $title = new JobTitle((array)$req);
            $id    = $this->repo->save($title);
            return new Response($id);
        }
        catch (\Exception $e) {
            return new Response($req->id, [$e->getMessage()]);
        }
    }

    public static function validate(Request $req): array
    {
        $errors = [];
        foreach ($req as $k=>$v) {
            if (empty($v)) { $errors[] = "jobTitles/missing_$k"; }
        }
        return $errors;
    }
}
