<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->errorMessages
 */
$errors = [];
foreach ($this->errorMessages as $e) {
    $error = $e->getMessage();
    if (file_exists(APPLICATION_HOME."/blocks/html/errorMessages/$error.inc")) {
        ob_start();
        $this->_include("json/errorMessages/$error.inc");
        $errors[] = ob_get_clean();

    }
    else {
        $errors[] = $error;
    }
}
echo json_encode(['errors'=>$errors], JSON_PRETTY_PRINT);
