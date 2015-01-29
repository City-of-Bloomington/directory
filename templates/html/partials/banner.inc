<?php
use Blossom\Classes\View;
?>
<header class="header-row">
    <div class="header-container">
        <div class="header-title">
            <h1><a href="<?php echo BASE_URL; ?>/"><?php echo View::escape(APPLICATION_NAME); ?></a></h1>
            <div class="header-location" id="location_name">
                <a href="<?php echo BASE_URL; ?>">City of Bloomington, Mark Kruzan, Mayor</a>
            </div>
        </div>
        <?php
            include APPLICATION_HOME.'/templates/html/partials/panel-widgets/User_menu.inc';
            include APPLICATION_HOME.'/templates/html/partials/panel-widgets/Administrator_menu.inc';
        ?>
    </div>
    <?php
        $this->_include('partials/menubar.inc');
    ?>
</header>
