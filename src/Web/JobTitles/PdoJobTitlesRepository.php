<?php
/**
 * SQL Server repository for JobTitles
 *
 * NOTE:
 * The ODBC driver for SQL Server does not handle bound parameters.
 * It does not correctly match bound parameter data types to database data types
 * So, we have to carefully clean all inputs into SQL queries, and execute
 * them without using bound parameters.
 *
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\JobTitles;

use Aura\SqlQuery\Common\SelectInterface;
use Aura\SqlQuery\QueryFactory;
use Domain\JobTitles\DataStorage\JobTitlesRepository;
use Domain\JobTitles\Entities\JobTitle;
use Web\PdoRepository;

class PdoJobTitlesRepository extends PdoRepository implements JobTitlesRepository
{
    public function __construct(\PDO $pdo)
    {
        $this->pdo          = $pdo;
        $this->queryFactory = new QueryFactory('sqlsrv');
    }

    public function load(int $id): JobTitle
    {
        $sql    = "select id, Code as code, Title as title
                   from COB.jobTitleCrosswalk
                   where id=$id";
        $query = $this->pdo->query($sql);
        if ($query) {
            $result = $query->fetchAll(\PDO::FETCH_ASSOC);
            return new JobTitle($result[0]);
        }
        throw new \Exception('jobTitles/unknown');
    }

    public function find(): array
    {
        $sql    = "select id, Code as code, Title as title
                   from COB.jobTitleCrosswalk
                   order by code";
        $result = $this->pdo->query($sql);
        $titles = [];
        foreach ($result->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $titles[] = new JobTitle($row);
        }
        return $titles;
    }

    /**
     * Clean a string, making it safe to use in an SQL command
     */
    private static function clean(string $value): string
    {
        return preg_replace('/[^a-zA-Z\-\s]/', '', $value);
    }

    public function save(JobTitle $jobTitle): int
    {
        $code  = self::clean($jobTitle->code );
        $title = self::clean($jobTitle->title);
        if ($jobTitle->id) {
            $sql = "update COB.jobTitleCrosswalk
                    set Code='$code', Title='$title'
                    where id={$jobTitle->id}";
            $result = $this->pdo->query($sql);
            if ($result !== false) {
                return $jobTitle->id;
            }
        }
        else {
            $sql = "insert into COB.jobTitleCrosswalk (Code, Title)
                    output inserted.id
                    values('$code', '$title')";
            $result = $this->pdo->query($sql);
            if ($result !== false) {
                $row = $result->fetch(\PDO::FETCH_ASSOC);
                return (int)$row['id'];
            }
        }

        $e = $result->errorInfo();
        throw new \Exception($e[2]);
    }
}
