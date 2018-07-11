<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
$query = !empty($_GET['query']) ? parent::escape($_GET['query']) : '';
$this->template->addToAsset('scripts', BASE_URI.'/js/search.js');
?>
<h1><?= $this->_('search'); ?></h1>
<p><?= $this->_('search_help', 'messages'); ?></p>
<form method="get" action="<?= BASE_URI; ?>/people/search" class="searchForm">
    <fieldset>
        <input name="query" id="search_query" placeholder="<?= $this->_('search_placeholder', 'messages'); ?>" value="<?= $query; ?>" />
    </fieldset>
</form>
