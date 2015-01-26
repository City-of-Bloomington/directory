<?php
use Application\Models\User;
use Blossom\Classes\View;
use Blossom\Classes\Url;
?>
<header class="header-row">
    <div class="header-container">
        <div class="header-title">
            <h1><a href="<?php echo BASE_URL; ?>/"><?php echo View::escape(APPLICATION_NAME); ?></a></h1>
            <div class="header-location" id="location_name">
                <a href="<?php echo BASE_URL; ?>">City of Bloomington, Mark Kruzan, Mayor</a>
            </div>
        </div>
        <div id="utilityBar" class="header-utilityBar">
            <?php
                $a = '<a href="%s">%s</a>';
                if (isset($_SESSION['USER'])) {
                    $this->addToAsset('scripts', JQUERY.'/jquery.min.js');
                    $this->addToAsset('scripts', BASE_URI.'/js/dropdown.js');
                    $this->addToAsset('scripts', BASE_URI.'/js/usermenu.js');

                    echo "
                    <div id=\"userDropdownLauncher\" class=\"ext-launcher\"
                         aria-haspopup=\"true\" aria-expanded=\"false\">
                        <i class=\"fa fa-chevron-down\"></i>
                    ";
                    echo View::escape($_SESSION['USER']->getFullname());
                    echo "
                    </div>
                    <div class=\"ext-links closed\" aria-hidden=\"true\" id=\"userDropdown\">
                    ";
                        echo sprintf($a, BASE_URI.'/login/logout', $this->_('logout'));
                    echo "
                    </div>
                    ";
                }
                else {
                    $return_url = View::escape($_SERVER['REQUEST_URI']);
                    echo sprintf($a, BASE_URI.'/login?return_url='.$return_url, $this->_('login'));
                }
            ?>
        </div>
        <div class="site-utilityBar">
            <div class="ext-launcher" id="siteSettingsLauncher" aria-haspopup="true" aria-expanded="false"><i class="fa fa-gear"></i> Settings</div>
            <div class="ext-links closed" id="siteSettingsDropdown">
                <?php
                    $routes = [
                        'users'      => 'user'
                    ];
                    foreach ($routes as $plural=>$singular) {
                        $requiredAction = 'index';
                        if (User::isAllowed($plural, $requiredAction)) {
                            echo sprintf($a, BASE_URI.'/'.$plural, $this->_([$singular, $plural, 2]));
                        }
                    }
                ?>
            </div>
        </div>
    </div>
    <?php
        $this->_include('partials/menubar.inc');
    ?>
</header>
