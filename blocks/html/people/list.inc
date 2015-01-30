<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->people
 */
use Blossom\Classes\View;

if (count($this->people)) {
    echo '<table class="output-tabular">';
    echo '<thead><tr><th scope="col">Name</th><th scope="col">Title</th><th scope="col">Phone</th><th scope="col">Email</th></tr></thead><tbody>';
    foreach ($this->people as $p) {
        $url = BASE_URI.'/people/view?username='.$p->getUsername();
        $name = View::escape($p->getFullname());
        $title = View::escape($p->getTitle());
        $phone = current($p->getPhones());

        echo "
        <tr><td><a href=\"$url\">$name</a></td>
            <td>$title</td>
            <td class=\"ext-number\">$phone</td>
            <td><a href=\"mailto:{$p->getEmail()}\">{$p->getEmail()}</a></td>
        </tr>
        ";
    }
    echo '</tbody></table>';
} else {
    $query = !empty($_GET['query']) ? View::escape($_GET['query']) : null;
    echo "
        <h1>No results found</h1>
        <p class=\"text-help\">Please double-check your spelling and try your search again. If that doesn&lsquo;t work, try browsing through the city&lsquo;s <a href=\"".BASE_URI."\">Directory of Departments</a>.</p>
    ";
}
