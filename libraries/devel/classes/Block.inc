<?php
/**
 * @copyright Copyright (C) 2006,2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 *
 * Represents a block of main content in a template
 */
	class Block extends View
	{
		private $filename;

		public function __construct($filename,array $vars=null)
		{
			$this->filename = $filename;
			if (count($vars))
			{
				foreach($vars as $name=>$value) { $this->vars[$name] = $value; }
			}
		}

		public function render($outputFormat='html')
		{
			include APPLICATION_HOME."/blocks/$outputFormat/{$this->filename}";
		}
	}
?>