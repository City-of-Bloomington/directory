<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->adRecords
 * @param array $this->hrRecords
 */
use Application\Models\DirectoryAttributes;
?>
<section id="synchronizeForm">
    <form method="get" action="<?= BASE_URI; ?>/synchronize/compare">
        <header>
            <button type="submit"><?= $this->_('compare'); ?></button>
        </header>
        <fieldset>
            <legend>
            <?php
                $count = count($this->adRecords);
                echo "{$this->_('notInNewWorld')} ($count)";
            ?>
            </legend>
            <div class="container">
            <?php
                $fieldname = DirectoryAttributes::USERNAME;

                $fields = ['title', 'firstname', 'lastname', 'department', 'username'];
                foreach ($this->adRecords as $p) {
                    $url   = BASE_URI.'/people/view?username='.$p['username'];
                    foreach ($fields as $f) { $$f = self::escape($p[$f]); }

                    echo "
                    <article>
                        <header>
                            <label>
                                <input name=\"$fieldname\" type=\"radio\" value=\"$username\" />
                                $firstname $lastname
                            </label>
                        </header>
                        <div>$title</div>
                        <div>$department</div>
                        <div><a href=\"$url\">$username</a></div>
                    </article>
                    ";
                }
            ?>
            </div>
        </fieldset>

        <fieldset>
            <legend>
            <?php
                $count = count($this->hrRecords);
                echo "{$this->_('notInActiveDirectory')} ($count)";
            ?>
            </legend>
            <div class="container">
            <?php
                $fieldname = DirectoryAttributes::EMPLOYEENUM;

                $fields = ['title', 'firstname', 'lastname', 'employeeNum', 'department'];
                foreach ($this->hrRecords as $p) {
                    foreach ($fields as $f) { $$f = self::escape($p[$f]); }

                    echo "
                    <article>
                        <header>
                            <label>
                                <input name=\"$fieldname\" type=\"radio\" value=\"$employeeNum\" />
                                $firstname $lastname
                            </label>
                        </header>
                        <div>$title</div>
                        <div>$department</div>
                        <div>$employeeNum</div>
                    </article>
                    ";
                }
            ?>
            </div>
        </fieldset>
    </form>
</section>