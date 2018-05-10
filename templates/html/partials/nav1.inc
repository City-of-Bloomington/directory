<div class="nav1">
    <nav class="container">
		<?php
			$a = '<a href="%s">%s</a>';
			echo sprintf($a, BASE_URI, $this->_('search'));
			echo sprintf($a, BASE_URI.'/departments', $this->_(['department', 'departments', 2]));
			echo sprintf($a, BASE_URI.'/departments/numbers', $this->_('main_numbers'));
		?>
    </nav>
</div>
