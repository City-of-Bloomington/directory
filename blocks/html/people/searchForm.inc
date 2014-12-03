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
?>
<section>
    <h1><?php echo $this->_('search'); ?></h1>
    <form method="get" action="<?php echo BASE_URI; ?>/people/search">
        <fieldset><legend><?php echo $this->_('search_by_name', 'messages'); ?></legend>
            <p><?php echo $this->_('search_by_name_help', 'messages'); ?></p>
            <table>
            <tr><td><label for="lastname"><?php echo $this->_('lastname'); ?></label></td>
                <td><input name="lastname" id="lastname" value="<?php echo $lastname; ?>" /></td></tr>
            <tr><td><label for="firstname"><?php echo $this->_('firstname'); ?></label></td>
                <td><input name="firstname" id="firstname" value="<?php echo $firstname; ?>" /></td></tr>
            </table>
            <button type="submit"><?php echo $this->_('search'); ?></button>
        </fieldset>
        <fieldset><legend><?php echo $this->_('search_by_phone', 'messages'); ?></legend>
            <p><?php echo $this->_('search_by_phone_help', 'messages'); ?></p>
            <table>
            <tr><td><label for="extension"><?php echo $this->_('extension'); ?></label></td>
                <td><input name="extension" id="extension" value="<?php echo $extension; ?>" /></td></tr>
            </table>
            <button type="submit" class="search"><?php echo $this->_('search'); ?></button>
        </fieldset>
    </form>
</section>