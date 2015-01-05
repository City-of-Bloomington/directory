<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
use Blossom\Classes\View;

# Clean all the stuff they typed
$lastname  = !empty($_GET['lastname' ]) ? View::escape($_GET['lastname' ]) : "";
$firstname = !empty($_GET['firstname']) ? View::escape($_GET['firstname']) : "";
$extension = !empty($_GET['extension']) ? View::escape($_GET['extension']) : "";
$inputHelper = $this->template->getHelper('Input');
?>
<section>
    <h1><?php echo $this->_('search'); ?></h1>
    <form class="form-search" method="get" action="<?php echo BASE_URI; ?>/people/search">
        <fieldset class="form-fieldset"><legend><?php echo $this->_('search_by_name', 'messages'); ?></legend>
            <p><?php echo $this->_('search_by_name_help', 'messages'); ?></p>

            <?php echo $inputHelper->textInput('lastname', $this->_('lastname'), $lastname); ?>
            <?php echo $inputHelper->textInput('firstname', $this->_('firstname'), $firstname); ?>

            <div class="input-buttons">
                <button type="submit"><i class="fa fa-search"></i> <?php echo $this->_('search'); ?></button>
            </div>
        </fieldset>
        <fieldset class="form-fieldset"><legend><?php echo $this->_('search_by_phone', 'messages'); ?></legend>
            <p><?php echo $this->_('search_by_phone_help', 'messages'); ?></p>
            <?php echo $inputHelper->textInput('extension', 'Extension', $extension); ?>

            <div class="input-buttons">
                <button type="submit" class="search"><i class="fa fa-search"></i> <?php echo $this->_('search'); ?></button>
            </div>
        </fieldset>
    </form>
</section>
