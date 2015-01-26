<?php
/**
 * @copyright 2013-2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Templates\Helpers;

use Blossom\Classes\Helper;

class SaveAndCancelButtons extends Helper
{
	public function saveAndCancelButtons($cancelURL, $onclick=null)
	{
		$buttons = "
                <div class=\"input-buttons\">
                    <button type=\"submit\"><i class=\"fa fa-save\"></i>
                            {$this->template->_('save')}
                    </button>
                    <a class=\"button\" href=\"$cancelURL\" $onclick><i class=\"fa fa-undo\"></i>
                            {$this->template->_('cancel')}
                    </a>
                </div>
		";
		return $buttons;
	}
}
