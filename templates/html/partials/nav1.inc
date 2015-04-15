<?php
use Application\Models\User;
?>

<div class="nav1">
    <nav class="container">
		<?php
			$a = '<a href="%s">%s</a>';
			echo sprintf($a, BASE_URI, $this->_(['department', 'departments', 2]));
			echo sprintf($a, BASE_URI.'/departments/numbers', $this->_('main_numbers'));
			echo sprintf($a, BASE_URI.'/people/search', $this->_('search_advanced'));
		?>
		<form method="get" action="<?php echo BASE_URI; ?>/people/search" class="nav1-searchForm">
			<fieldset>
				<input name="query" placeholder="<?php echo $this->_('search'); ?>" type="text" />
			</fieldset>
		</form>
    </nav>
</div>
