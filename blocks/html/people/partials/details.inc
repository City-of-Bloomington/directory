<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Person $this->person
 * @param boolean $this->disableButtons
 */
use Application\Models\User;
?>
<header>
    <h1><?= self::escape($this->person->getFullname()); ?></h1>
    <?php
        if (!$this->disableButtons && User::isAllowed('people', 'update')) {
            echo '<div class="tools">';
            $h = $this->template->getHelper('buttonLink');
            echo $h->buttonLink(
                BASE_URI.'/people/update?username='.$this->person->username,
                $this->_('person_edit'),
                'edit'
            );
            echo '</div>';
        }
    ?>
</header>
 <h2><?= self::escape($this->person->title);      ?></h2>
<div><?= self::escape($this->person->department); ?></div>
<div><?= self::escape($this->person->division);   ?></div>
<div><?= self::escape($this->person->location);   ?></div>
