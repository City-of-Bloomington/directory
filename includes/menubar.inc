<div class="menuBar">
	<form method="get" action="search.php">
		<fieldset>
			<a href="<?php echo $BASE_URL; ?>/mainNumbers.php">Main Numbers</a>
			<a href="<?php echo $BASE_URL; ?>/searchForm.php">Search</a>

			<label>Last<input name="lastname" id="lastname" /></label>
			<label>First<input name="firstname" id="firstname" /></label>
			<label>Ext<input name="extension" id="extension" /></label>
			<button type="submit" class="search">Search</button>
		</fieldset>
	</form>
</div>
