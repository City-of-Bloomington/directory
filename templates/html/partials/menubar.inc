<?php
use Application\Models\User;
?>
<nav>
	<?php
		$a = '<a href="%s">%s</a>';
		echo sprintf($a, BASE_URI, $this->_(['department', 'departments', 2]));
		echo sprintf($a, BASE_URI.'/departments/numbers', $this->_('main_numbers'));
		echo sprintf($a, BASE_URI.'/people/search', $this->_('search_advanced'));
		if (User::isAllowed('users', 'edit')) {
            echo sprintf($a, BASE_URI.'/users', $this->_(['user', 'users', 2]));
		}
	?>
	<form method="get" action="<?php echo BASE_URI; ?>/people/search">
        <fieldset>
            <input name="query" />
            <button type="submit"><?php echo $this->_('search'); ?></button>
        </fieldset>
	</form>
</nav>
