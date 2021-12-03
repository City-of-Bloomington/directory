<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\EmergencyContacts\Views;

use Web\View;
use Domain\EmergencyContacts\Actions\Update\Request;

class UpdateView extends View
{
    public function __construct(Request $req)
    {
        parent::__construct();

        $this->vars = (array)$req;

    }

    public function render(): string
    {
        return $this->twig->render('html/emergencyContacts/updateForm.twig', $this->vars);
    }
}
