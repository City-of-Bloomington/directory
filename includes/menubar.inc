<div class="menuBar">
	<form method="get" action="search.php">
		<fieldset>
			<a href="<?php echo $BASE_URL; ?>/mainNumbers.php">Main Numbers</a>
			<a href="<?php echo $BASE_URL; ?>">Search</a>

			<label>Last<input name="lastname" size="6" /></label>
			<label>First<input name="firstname" size="6" /></label>
			<label>Ext<input name="extension" size="6" /></label>
			<button type="submit" class="search">Search</button>
		</fieldset>
	</form>
</div>
