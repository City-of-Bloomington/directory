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
    public function text($id, $label, $value = '', $type = 'text', array $inputAttributesArray = [])
    {
        $extraAttributes = '';
        foreach ($inputAttributesArray as $attribute => $attrValue)
        {
            $extraAttributes .= " $attribute=\"$attrValue\"";
        }
        
        echo "
            <dl class=\"input-field\">
                <dt><label for=\"$id\">$label</label></dt>
                <dd><input id=\"$id\" name=\"$id\" value=\"$value\" type=\"$type\" $extraAttributes /></dd>
            </dl>
        ";
    }
}
