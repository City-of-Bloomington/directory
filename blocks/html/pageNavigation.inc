<?php
/**
 * Displays page navigation for any list that has pagination turned on
 *
 * @copyright 2007-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Paginator $this->paginator
 */
use Application\Classes\Url;
$pages = $this->paginator->getPages();

if (count($pages->pagesInRange) > 1) {

	$url = new Url($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	$url->purgeEmptyParameters();

	echo '<nav class="pageNavigation">';

	// Show the Back button
	if (isset($pages->previous)) {
		$url->page = $pages->first;
		echo "<a href=\"$url\" class=\"first\">First</a>";

		$url->page = $pages->previous;
		echo "<a href=\"$url\" class=\"previous\">Back</a>";
	}
	// Show the page number links
	// Show only $maxNumLinks pages at a time
	foreach ($pages->pagesInRange as $page) {
		$url->page = $page;
		$class = ($page == $pages->current) ? 'class="current"' : '';
		echo "<a href=\"$url\" $class>$page</a>";
	}

	// Show the Next button
	if (isset($pages->next)) {
		$url->page = $pages->next;
		echo "<a href=\"$url\" class=\"next\">Next</a>";

		$url->page = $pages->last;
		echo "<a href=\"$url\" class=\"last\">Last</a>";
	}

	echo '</nav>';
}
