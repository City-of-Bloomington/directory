<?php
/**
 * @copyright 2014-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->people
 */
$heading = $this->title ? "<h1>{$this->title}</h1>" : '';

if (count($this->people)) {
    echo "
    <section>
        $heading

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
        $url   = BASE_URI.'/people/view?username='.$p->username;
        $name  = self::escape($p->getFullname());
        $title = self::escape($p->title);

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
    </section>
    ";
}
