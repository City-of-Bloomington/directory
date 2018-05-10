<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->people
 */
$count = count($this->people);
if ($count) {
    $this->_include('people/list.inc');
}
else {
    echo "
    <section>
        <h1>{$this->_('noResults')}</h1>
        <p class=\"text-help\">
            {$this->_('noResults_help', 'messages')}
        </p>
    </section>
    ";
}
