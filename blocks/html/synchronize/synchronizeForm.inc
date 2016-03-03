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
        <fieldset><legend><?= $this->_('notInNewWorld'); ?></legend>
            <div class="container">
            <?php
                $fieldname = DirectoryAttributes::USERNAME;

                foreach ($this->adRecords as $p) {
                    $url   = BASE_URI.'/people/view?username='.$p['username'];
                    $title = self::escape($p['title']);
                    $name  = self::escape($p['name']);
                    $department  = $p['department' ];
                    $username    = $p['username'   ];

                    echo "
                    <article>
                        <header>
                            <label>
                                <input name=\"$fieldname\" type=\"radio\" value=\"$username\" />
                                $name
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

        <fieldset><legend><?= $this->_('notInActiveDirectory'); ?></legend>
            <div class="container">
            <?php
                $fieldname = DirectoryAttributes::EMPLOYEENUM;
                
                foreach ($this->hrRecords as $p) {
                    $title = self::escape($p['title']);
                    $name  = self::escape($p['name']);

                    $employeeNum = $p['employeeNum'];
                    $department  = $p['department' ];

                    echo "
                    <article>
                        <header>
                            <label>
                                <input name=\"$fieldname\" type=\"radio\" value=\"$employeeNum\" />
                                $name
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