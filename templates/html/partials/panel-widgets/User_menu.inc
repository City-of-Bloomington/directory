<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
use Blossom\Classes\View;
?>
<div id="utilityBar" class="site-utilityBar">
    <?php
        if (isset($_SESSION['USER'])) {
            $this->addToAsset('scripts', JQUERY.'/jquery.min.js');
            $this->addToAsset('scripts', BASE_URI.'/js/dropdown.js');
            $this->addToAsset('scripts', BASE_URI.'/js/usermenu.js');

            $name = View::escape($_SESSION['USER']->getFullname());

            echo "
            <div id=\"userDropdownLauncher\" class=\"ext-launcher\" aria-haspopup=\"true\" aria-expanded=\"false\">
                <i class=\"fa fa-chevron-down\"></i>
                $name
            </div>
            <div class=\"ext-links closed\" aria-hidden=\"true\" id=\"userDropdown\">
                <a href=\"".BASE_URI."/people/updateEmergencyContacts?username={$_SESSION['USER']->getUsername()}\"><i class=\"fa fa-pencil-square-o\"></i> {$this->_('emergencyContacts_update')}</a>
                <a href=\"".BASE_URI."/login/logout\"><i class=\"fa fa-sign-out\"></i> {$this->_('logout')}</a>
            </div>
            ";
        }
        else {
            $return_url = View::escape($_SERVER['REQUEST_URI']);
            echo "<a href=\"".BASE_URI."/login?return_url=$return_url\"><i class=\"fa fa-sign-in\"></i> {$this->_('login')}</a>";
        }
    ?>
</div>
