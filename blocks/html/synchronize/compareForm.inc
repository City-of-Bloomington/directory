<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
use Application\Models\DirectoryAttributes;
use Blossom\Classes\Block;

$username    = $this->adRecord->username;
$employeeNum = $this->hrRecord['employeeNum'];
?>
<section>
    <header>
        <h1><?= $this->_('synchronize'); ?></h1>
    </header>

    <form method="post" action="<?= BASE_URI; ?>/synchronize/update">
        <fieldset><legend><?= $this->_('adRecord'); ?></legend>
        <?php
            $block = new Block('people/partials/details.inc', [
                'person'         => $this->adRecord,
                'disableButtons' => true
            ]);
            echo $block->render('html', $this->template);

            $block = new Block('directoryAttributes/contactInfo.inc', ['entry'=>$this->adRecord]);
            echo $block->render('html', $this->template);

            echo "
            <dl><dt><label  for=\"employeeNum\">{$this->_('employeeNum')}</label></dt>
                <dd><input name=\"employeeNum\" id=\"employeeNum\" value=\"$employeeNum\" /></dd>
            </dl>
            ";
        ?>
        </fieldset>

        <fieldset><legend><?= $this->_('hrRecord'); ?></legend>
        <?php
            $block = new Block('synchronize/hrInfo.inc', ['hrRecord'=>$this->hrRecord]);
            echo $block->render('html', $this->template);

            echo "
            <dl><dt><label  for=\"username\">{$this->_('username')}</label></dt>
                <dd><input name=\"username\" id=\"username\" value=\"$username\" /></dd>
            </dl>
            ";
        ?>
        </fieldset>
    </form>
</section>