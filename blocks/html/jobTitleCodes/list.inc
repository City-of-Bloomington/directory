<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->jobTitleCodes
 */
use Application\Models\User;

$helper = $this->template->getHelper('buttonLink');
?>
<section>
    <header>
        <h1><?= $this->_(['title', 'titles', count($this->jobTitleCodes)]); ?></h1>
        <div class="tools">
        <?php
            if (User::isAllowed('jobTitleCodes', 'add')) {
                echo $helper->buttonLink(
                    BASE_URI.'/jobTitleCodes/update',
                    $this->_('jobTitleCodes_add'),
                    'add'
                );
            }
        ?>
        </div>
    </header>
    <table>
    <?php
        $userCanEdit = User::isAllowed('jobTitleCodes', 'edit');
        $editButton = '';

        foreach ($this->jobTitleCodes as $t) {
            $code  = self::escape($t->getCode());
            $title = self::escape($t->getTitle());
            if ($userCanEdit) {
                $editButton = $helper->buttonLink(
                    BASE_URI.'/jobTitleCodes/update?id='.$t->getId(),
                    $this->_('jobTitleCodes_edit'),
                    'edit'
                );
            }
            echo "
            <tr><th>$code</th>
                <td>$title</td>
                <td>$editButton</td>
            </tr>
            ";
        }
    ?>
    </table>
</section>