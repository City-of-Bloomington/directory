<?php
use Blossom\Classes\View;
?>
<header>
    <div class="container">
        <div class="site-title">
            <h1><a href="<?php echo BASE_URL; ?>/"><?php echo View::escape(APPLICATION_NAME); ?></a></h1>
            <div class="site-location" id="location_name">
                <a href="<?php echo BASE_URL; ?>">City of Bloomington, Mark Kruzan, Mayor</a>
            </div>
        </div>
        <div class="site-utilityBar">
        <?php
            $this->addToAsset('scripts', JQUERY.'/jquery.min.js');
            $this->addToAsset('scripts', BASE_URI.'/js/menus.js');

            include APPLICATION_HOME.'/templates/html/partials/panel-widgets/User_menu.inc';
            include APPLICATION_HOME.'/templates/html/partials/panel-widgets/Administrator_menu.inc';
        ?>
        </div>
    </div>
    <?php $this->_include('partials/nav1.inc'); ?>
</header>
