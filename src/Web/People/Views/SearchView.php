<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People\Views;

use Web\View;
use Domain\People\Actions\Search\Response;

class SearchView extends View
{
    public function __construct(Response $res, ?string $query=null)
    {
        parent::__construct();

        $this->vars = [
            'people' => $res->people,
            'query'  => $query
        ];
    }

    public function render(): string
    {
        return $this->twig->render('html/people/searchForm.twig', $this->vars);
    }
}
