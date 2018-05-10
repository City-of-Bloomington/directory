<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
$query = !empty($_GET['query']) ? parent::escape($_GET['query']) : '';
?>
<form method="get" action="<?= BASE_URI; ?>/people/search" class="searchForm">
    <fieldset>
        <input name="query" placeholder="<?= $this->_('search_help', 'messages'); ?>" value="<?= $query; ?>" />
    </fieldset>
</form>
