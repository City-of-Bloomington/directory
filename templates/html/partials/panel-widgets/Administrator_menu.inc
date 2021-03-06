<?php
/**
 * @copyright 2015-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
use Application\Models\User;

$links = '';
if (User::isAllowed('users')) {
    $routes = [
        'users'             => 'user',
        'emergencyContacts' => 'emergencyContact',
        'jobTitleCodes'     => 'jobTitleCode'
    ];
    foreach ($routes as $plural=>$singular) {
        $requiredAction = 'index';
        if (User::isAllowed($plural, $requiredAction)) {
            $links.= "<a href=\"".BASE_URI."/$plural\">{$this->_([$singular, $plural, 2])}</a>";
        }
    }
    if (User::isAllowed('synchronize')) {
        $links.= "<a href=\"".BASE_URI."/synchronize\">{$this->_('synchronize')}</a>";
    }
}
?>
<?php if ($links): ?>
<nav>
    <div class="menuLauncher">
        <i class="fa fa-gear"></i>
        <?php echo $this->_('administration'); ?>
    </div>
    <div class="menuLinks closed">
        <?php echo $links; ?>
    </div>
</nav>
<?php endif; ?>