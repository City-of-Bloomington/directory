<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People\Views;

use Web\View;
use Domain\People\Actions\Update\Request;
use Domain\People\Entities\Person;

class UpdateView extends View
{
    public function __construct(Request $req, Person $person)
    {
        parent::__construct();
        
        $this->vars = array_merge((array)$req, [
            'person' => $person
        ]);
    }
    
    public function render(): string
    {
        return $this->twig->render('html/people/updateForm.twig', $this->vars);
    }
}
