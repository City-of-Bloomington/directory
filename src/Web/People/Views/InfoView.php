<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People\Views;

use Web\View;
use Domain\People\Entities\Person;

class InfoView extends View
{
    public function __construct(Person $person)
    {
        parent::__construct();

        $this->vars = [
            'person' => $person
        ];
    }

    public function render(): string
    {
        return $this->twig->render('html/people/info.twig', $this->vars);
    }
}
