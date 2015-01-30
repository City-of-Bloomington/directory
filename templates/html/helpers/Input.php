<?php
/**
* Provides markup for button links
*
* @copyright 2014-2015 City of Bloomington, Indiana
* @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
* @author Dan Hiester <hiesterd@bloomington.in.gov>
*/
namespace Application\Templates\Helpers;

use Blossom\Classes\Helper;

class Input extends Helper
{

    public function text($id, $label, $value = '', $type = 'text', $required = false, $inputAttributesArray = [])
    {
        $extraAttributes = '';
        $required == false ? '' : '<abbr title="Required field" class="required">*</abbr> ';
        foreach ($inputAttributesArray as $attribute => $attrValue)
        {
            $extraAttributes .= "$attribute=\"$attrValue\" ";
        }
        echo <<<EOT

            <dl class="input-field">
                <dt><label for="$id">{$required}$label</label></dt>
                <dd><input id="$id" name="$id" value="$value" type="$type" $extraAttributes/></dd>
            </dl>
EOT;
    }
}
