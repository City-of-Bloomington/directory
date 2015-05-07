<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->people
 */
use Blossom\Classes\Block;

$people = [];
foreach ($this->people as $p) {
    $people[] = $p->getData();
}
echo json_encode($people, JSON_PRETTY_PRINT);