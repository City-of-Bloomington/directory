<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @param define SVN The path to the subversion working copy
 */
class SubversionWorkingCopy
{
	private $path;


	/**
	 *	 Takes a local directory path and expects it to be a checked out
	 * copy from a subversion repository
	 */
	public function __construct($path)
	{
		if (is_dir($path))
		{
			$this->path = $path;

			# Try and run a subversion command on it, to make
			# sure it's a working copy
			$this->status();
		}
		else { throw new Exception("$path is not a directory"); }
	}

	/**
	 * Adds a file or directory to subversion.
	 * Path is relative to the working copy's base dir
	 */
	public function add($path,$flags=null)
	{
		$out = $this->runCommand(SVN." add $path $flags");
		return $out;
	}

	/**
	 * Deletes a file or directory from subversion.
	 * Path is relative to the working copy's base dir
	 */
	public function delete($path,$flags=null)
	{
		return $this->runCommand(SVN." delete $path $flags");
	}

	/**
	 * Does a commit on Path, if give, or the entire
	 * working copy, if Path is null
	 */
	public function commit($path=null,$logMessage)
	{
		if (!$path) { $path = $this->path; }
		if ($this->status($path))
		{
			$out = $this->runCommand(SVN." commit $path -m '$logMessage'");
			if (!preg_match("/^Committed/",end($out))) { throw new Exception(end($out)); }
			return $out;
		}
	}

	/**
	 * Returns the output of a status call as an array.
	 * One line of the output per array element
	 */
	public function status($path=null)
	{
		if (!$path) { $path = $this->path; }
		return $this->runCommand(SVN." status $path");
	}

	private function runCommand($command)
	{
		$command = escapeshellcmd($command);
		$lastLine = exec("$command 2>&1",$out);
		if (preg_match("/^svn:(.*)/",$lastLine,$matches)) { throw new Exception($matches[1]); }
		return $out;
	}
}
?>