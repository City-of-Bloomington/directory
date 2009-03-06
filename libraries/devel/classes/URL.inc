<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 *
 * Helper class for URL handling.  Parses URLs and allows adding parameters from variables.
 *
 * $url = new URL('/path/to/webpage.php?initialParameter=whatever');
 * $url->parameters['somevar'] = $somevar;
 * echo $url->getURL();
 */
	class URL
	{
		public $script;
		public $parameters = array();

		public function __construct($script="",$parameters=array())
		{
			$this->parameters = $parameters;

			# Parse any parameters already in the script
			if (ereg("\?",$script))
			{
				list($this->script,$parameters) = explode("?",$script);

				$parameters = preg_split('/[;&]/',$parameters);
				foreach($parameters as $parameter)
				{
					if (ereg("=",$parameter))
					{
						list($field,$value) = explode("=",$parameter);
						if ($value) { $this->parameters[$field] = $value; }
					}
				}
			}
			else { $this->script = $script; }
		}

		public function __toString() { return $this->getURL(); }

		public function getURL()
		{
			$url = $this->script;
			if (count($this->parameters))
			{
				$url.= "?";
				foreach($this->parameters as $key=>$value) { $url.= urlencode($key).'='.urlencode($value).';'; }
			}
			return $url;
		}
	}
?>