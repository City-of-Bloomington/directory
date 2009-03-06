<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
	abstract class PDOResultIterator extends Database implements ArrayAccess,SeekableIterator,Countable
	{
		protected $select = "";
		protected $joins = "";
		protected $where = "";
		protected $sort = "";
		protected $limit = "";
		protected $groupBy = ""; # IUB Libraries - Added groupBy to sql querying for grouping option
		private $sql;

		protected $list = array();
		private $valid = false;
		private $cacheEnabled = true;
		private $cache = array();
		private $key;

		protected function populateList(array $options=null)
		{
			global $PDO;

			if (count($options)) { $this->where = ' where '.implode(' and ',$options); }
			$orderBy = $this->sort ? "order by {$this->sort}" : '';
			$this->sql = "{$this->select} {$this->joins} {$this->where} {$this->groupBy} $orderBy {$this->limit}"; # IUB Libraries - Added groupBy to query

			$result = $PDO->query($this->sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['id']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($this->sql.$e[2]); }
		}

		public function getPagination($pageSize) { return new Paginator($this,$pageSize); }

		abstract public function find($fields=null,$sort='',$limit=null,$groupBy=null);
		abstract protected function loadResult($key);

		# Array Access section
		public function offsetExists($offset) { return array_key_exists($offset,$this->list); }
		public function offsetSet($offset,$value) { } # Read-only for now
		public function offsetUnset($offset) { } # Read-only for now
		public function offsetGet($offset)
		{
			if ($this->offsetExists($offset))
			{
				if ($this->cacheEnabled)
				{
					if (!isset($this->cache[$offset])) { $this->cache[$offset] = $this->loadResult($offset); }
					return $this->cache[$offset];
				}
				else { return $this->loadResult($offset); }
			}
			else { throw new OutOfBoundsException('Invalid seek position'); }
		}



		# Iterator Section
		public function rewind() { $this->key = 0; }
		public function next() { $this->key++; }
		public function key() { return $this->key; }
		public function valid() { return array_key_exists($this->key,$this->list); }
		public function current()
		{
			if ($this->cacheEnabled)
			{
				if (!isset($this->cache[$this->key])) { $this->cache[$this->key] = $this->loadResult($this->key); }
				return $this->cache[$this->key];
			}
			else { return $this->loadResult($this->key); }
		}
		public function seek($index)
		{
			if (isset($this->list[$index])) { $this->key = $index; }
			else { throw new OutOfBoundsException('Invalid seek position'); }
		}

		public function getIterator() { return $this; }

		# Countable Section
		public function count() { return count($this->list); }

		# Getters
		public function getSelect() { return $this->select; }
		public function getJoins() { return $this->joins; }
		public function getWhere() { return $this->where; }
		public function getGroupBy() { return $this->groupBy; } # IUB Libraries - Added getter for groupBy
		public function getSort() { return $this->sort; }
		public function getLimit() { return $this->limit; }
		public function getSQL() { return $this->sql; }

		# Cache Enable/Disable functions
		public function enableCache() { $this->cacheEnabled = true; }
		public function disableCache() { $this->cacheEnabled = false; }
	}
?>