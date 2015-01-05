<?php
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
                $link = '<a href="%s">%s</a>';

                if (isset($_SESSION['USER'])) {
                    echo sprintf($link, BASE_URI.'/login/logout', $this->_('logout'));

                    $name = View::escape($_SESSION['USER']->getFullname());
                    echo '<li>'.sprintf($this->_('signed_in_as', 'messages'), $name).'</li>';
                }
                else {
                    $return_url = Url::current_url();
                    echo sprintf($link, BASE_URI.'/login?return_url='.$return_url, $this->_('login'));
                }
            ?>
        </div>
    </div>
    <?php
        $this->_include('partials/menubar.inc');
    ?>
</header>
