<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
use Application\Models\User;

$links = '';
if (User::isAllowed('users')) {
    $routes = [
        'users'  => 'user',
        'emergencyContacts' => 'emergencyContact'
    ];
    foreach ($routes as $plural=>$singular) {
        $requiredAction = 'index';
        if (User::isAllowed($plural, $requiredAction)) {
            $links.= "<a href=\"".BASE_URI."/$plural\"><i class=\"fa fa-users\"></i> {$this->_([$singular, $plural, 2])}</a>";
        }
    }
}
?>
<?php if ($links): ?>
<div class="site-utilityBar">
    <div class="ext-launcher" id="siteSettingsLauncher" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-gear"></i>
        <?php echo $this->_('administration'); ?>
    </div>
    <div class="ext-links closed" id="siteSettingsDropdown">
        <?php echo $links; ?>
    </div>
</div>
<?php endif; ?>