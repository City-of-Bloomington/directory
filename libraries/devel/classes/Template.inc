<?php
/**
 * @copyright Copyright (C) 2006,2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 *
 * Defines the overall page layout
 */
	class Template extends View
	{
		private $filename;
		public $outputFormat = 'html';
		public $blocks = array();

		public function __construct($filename="default",$outputFormat='html',array $vars=null)
		{
			$this->filename = $filename;
			$this->outputFormat = $outputFormat;
			if (count($vars))
			{
				foreach($vars as $name=>$value) { $this->vars[$name] = $value; }
			}
		}

		# Template files must include a call to $this->includeBlocks(),
		# when they're ready for the main content
		public function render()
		{
			include(APPLICATION_HOME."/templates/{$this->filename}.inc");
		}

		# Callback function for template files
		private function includeBlocks()
		{
			foreach($this->blocks as $block) { $block->render($this->outputFormat); }
		}
	}
?>