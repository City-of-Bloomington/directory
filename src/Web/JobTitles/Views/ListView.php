<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\JobTitles\Views;

use Web\View;

class ListView extends View
{
    public function __construct(array $titles)
    {
        parent::__construct();

        $this->vars = ['titles' => $titles];
    }

    public function render(): string
    {
        return $this->twig->render('html/jobTitles/list.twig', $this->vars);
    }
}
