<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->people
 */
use Blossom\Classes\View;

if (count($this->people)) {
    echo '<table>';
    foreach ($this->people as $p) {
        $url = BASE_URI.'/people/view?username='.$p->getUsername();
        $name = View::escape($p->getFullname());
        $title = View::escape($p->getTitle());
        $phone = current($p->getPhones());

        echo "
        <tr><td><a href=\"$url\">$name</a></td>
            <td>$title</td>
            <td>$phone</td>
            <td><a href=\"mailto:{$p->getEmail()}\">{$p->getEmail()}</a></td>
        </tr>
        ";
    }
    echo '</table>';
}
