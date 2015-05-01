<?php
/**
 * @copyright 2014-2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->people
 */
use Blossom\Classes\View;

if (count($this->people)) {
    echo "
    <table>
        <thead>
            <tr><th scope=\"col\">{$this->_('name')}</th>
                <th scope=\"col\">{$this->_('title')}</th>
                <th scope=\"col\">{$this->_('phone')}</th>
                <th scope=\"col\">{$this->_('email')}</th>
            </tr>
        </thead>
        <tbody>
    ";
    foreach ($this->people as $p) {
        $url = BASE_URI.'/people/view?username='.$p->username;
        $name = View::escape($p->getFullname());
        $title = View::escape($p->title);

        $phone = $p->getMainPhone();

        echo "
        <tr><td><a href=\"$url\">$name</a></td>
            <td>$title</td>
            <td class=\"ext-number\">$phone</td>
            <td><a href=\"mailto:{$p->email}\">{$p->email}</a></td>
        </tr>
        ";
    }
    echo "
        </tbody>
    </table>
    ";
}
else {
    echo "
        <h1>No results found</h1>
        <p class=\"text-help\">
            Please double-check your spelling and try your search again.
            If that doesn&lsquo;t work, try browsing through the city&lsquo;s
            <a href=\"".BASE_URI."\">Directory of Departments</a>.
        </p>
    ";
}
