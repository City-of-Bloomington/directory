<?php
/*
	Required variables:		$pages

	$_GET variables:	page
*/
	if (count($pages) > 1)
	{
		$maxNumLinks = 10;
		$url = "$_SERVER[PHP_SELF]";

		$query = preg_replace("/page=(\d+)(;?)/","",$_SERVER['QUERY_STRING']);
		if ($query) { if ($query{strlen($query)-1} != ";") $query.=";"; }

		echo "<div class=\"pageNavigation\">";

		if ($_GET['page'] != 0)
		{
			$previous = $_GET['page']-1;
			echo "<button type=\"button\" class=\"back\" onclick=\"document.location.href='{$url}?{$query}page=$previous';\">Back</button>";
		}


		# Show only $maxNumLinks pages at a time
		$start = $_GET['page']<=$maxNumLinks/2 ? 0 : $_GET['page']-$maxNumLinks/2;
		$links = new LimitIterator($pages->getIterator(),$start,$maxNumLinks);
		foreach ($links as $i=>$index)
		{
			$displayNumber = $i+1;
			if ($i != $_GET['page']) { echo "<a href=\"{$url}?{$query}page=$i\">$displayNumber</a>"; }
			else { echo "<a href=\"{$url}?{$query}page=$i\" class=\"current\">$displayNumber</a>"; }
		}



		if ($_GET['page'] != $pages->getLastIndex())
		{
			$next = $_GET['page']+1;
			echo "<button type=\"button\" class=\"next\" onclick=\"document.location.href='{$url}?{$query}page=$next'\">Next</button>";
		}

		echo "</div>";
	}
?>