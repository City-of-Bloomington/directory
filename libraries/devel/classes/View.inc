<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
	abstract class View
	{
		protected $vars = array();

		abstract public function render();

		#----------------------------------------------------------------------------
		# These functions allow direct assigning of variables to the block
		#----------------------------------------------------------------------------
		public function __set($key,$value) { $this->vars[$key] = $value; }
		public function __get($key)
		{
			if (isset($this->vars[$key]))
			{
				if (is_string($this->vars[$key])) { return $this->escape($this->vars[$key]); }
				elseif (is_array($this->vars[$key])) { return new ArrayObject($this->vars[$key]); }
				else { return $this->vars[$key]; }
			}
			else return null;
		}
		public function __isset($key) { return array_key_exists($key,$this->vars); }

		#----------------------------------------------------------------------------
		# There are more bad characters than htmlspecialchars deals with.  We just want
		# to add in some other characters to clean.  While here, we might as well
		# have it trim out the whitespace too.
		#----------------------------------------------------------------------------
		public static function escape($string)
		{
			$string = htmlspecialchars(trim($string),ENT_QUOTES);
			$string = str_replace("\\","&#92;",$string);
			return $string;
		}

	}
?>