<nav>
<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
use Blossom\Classes\View;

if (isset($_SESSION['USER'])) {
    $name = View::escape($_SESSION['USER']->getFullname());

    echo "
    <div class=\"menuLauncher\">
        <i class=\"fa fa-chevron-down\"></i>
        $name
    </div>
    <div class=\"menuLinks closed\">
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
</nav>