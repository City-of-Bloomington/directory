<?php
/**
* Provides markup for button links
*
* @copyright 2014 City of Bloomington, Indiana
* @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
* @author Cliff Ingham <inghamn@bloomington.in.gov>
*/
namespace Application\Templates\Helpers;

use Blossom\Classes\Helper;

class Input extends Helper
{
    public function text($id, $label, $value = '', $inputClass = '')
    {
        $classText = $inputClass ? " class=\"$inputClass\"" : '';
        echo "
        <dl class=\"input-field\">
            <dt><label for=\"$id\">$label</label></dt>
            <dd><input type=\"text\" id=\"$id\" name=\"$id\" value=\"$value\"$classText /></dd>
        </dl>
        ";
    }
}
