<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param JobTitleCode $jobTitleCode
 */
$fields = ['id', 'code', 'title'];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f = self::escape($this->jobTitleCode->$get());
}
$h1 = $this->jobTitleCode->getId() ? $this->_('jobTitleCode_edit') : $this->_('jobTitleCode_add');
?>
<section>
    <h1><?= $h1; ?></h1>
    <form method="post" action="<?= BASE_URI; ?>/jobTitleCodes/update">
        <fieldset>
            <input name="id" type="hidden" value="<?= $id; ?>" />

            <?php
                foreach (['code', 'title'] as $f) {
                    echo "
                    <dl><dt><label for=\"$f\">{$this->_($f)}</label></dt>
                        <dd><input name=\"$f\" id=\"$f\" value=\"{$$f}\" required=\"true\" /></dd>
                    </dl>
                    ";
                }

                $helper = $this->template->getHelper('saveAndCancelButtons');
                echo $helper->saveAndCancelButtons(BASE_URI.'/jobTitleCodes');
            ?>
        </fieldset>
    </form>
</section>