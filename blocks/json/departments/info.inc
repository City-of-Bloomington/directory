<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Department $this->department
 */
use Application\Models\DirectoryAttributes as Attr;

$query = !empty($_GET[Attr::PROMOTED]) ? [Attr::PROMOTED => $_GET[Attr::PROMOTED]] : [];
echo json_encode($this->department->getData($query), JSON_PRETTY_PRINT);
