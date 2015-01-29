<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->people
 */
use Blossom\Classes\Block;

$json = [];
if (count($this->people)) {
    $block = new Block('people/info.inc');

    foreach ($this->people as $p) {
        $block->person = $p;
        $json[] = $block->render('json', $this->template);
    }
}
echo '['.implode(',', $json).']';