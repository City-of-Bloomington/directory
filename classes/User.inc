<?php
	require_once(GLOBAL_INCLUDES."/classes/SystemUser.inc");

	class User extends SystemUser
	{
		public function __construct($id = null)
		{
			if ($id)
			{
				# Load an existing user
				if (is_numeric($id)) { $sql = "select userID,username,password,authenticationMethod from users where userID=$id"; }
				else { $sql = "select userID,username,password,authenticationMethod from users where username='$id'"; }
				$result = mysql_query($sql) or die($sql.mysql_error());
				if (mysql_num_rows($result))
				{
					$user = mysql_fetch_array($result);

					$this->setUserID($user['userID']);
					$this->setUsername($user['username']);
					$this->setPassword($user['password']);
					$this->setAuthenticationMethod($user['authenticationMethod']);

					$this->loadRoles();
				}
				else { throw new Exception($sql); }
			}
		}

		public function save()
		{
			if ($this->getUserID()) { $this->update(); }
			else { $this->insert(); }

			$this->updateRoles();
		}

		public function delete()
		{
			$sql = "delete from userRoles where userID={$this->getUserID()}";
			mysql_query($sql) or die($sql.mysql_error());

			$sql = "delete from users where userID={$this->getUserID()}";
			mysql_query($sql) or die($sql.mysql_error());
		}


		private function update()
		{
			$sql = "update users set username='{$this->getUsername()}',authenticationMethod='{$this->getAuthenticationMethod()}'";
			if ($this->getPassword()) { $sql.=",password='".$this->getPassword()."'"; }
			$sql.=" where userID=".$this->getUserID();
			mysql_query($sql) or die($sql.mysql_error());
		}

		private function insert()
		{
			$sql = "insert users set username='{$this->getUsername()}',authenticationMethod='{$this->getAuthenticationMethod()}'";
			if ($this->getPassword()) { $sql.=",password='{$this->getPassword()}'"; }
			mysql_query($sql) or die($sql.mysql_error());

			$this->setUserID(mysql_insert_id());
		}

		private function updateRoles()
		{
			$sql = "delete from userRoles where userID={$this->getUserID()}";
			mysql_query($sql) or die($sql.mysql_error());

			$roles = $this->getRoles();

			foreach($roles as $role)
			{
				$sql = "insert userRoles set userID={$this->getUserID()},role=$role";
				mysql_query($sql) or die($sql.mysql_error());
			}
		}

		protected function loadRoles()
		{
			$roles = array();
			$sql = "select role from userRoles where userID={$this->getUserID()}";
			$result = mysql_query($sql) or die($sql.mysql_error());
			while(list($role) = mysql_fetch_array($result)) { $roles[] = $role; }
			$this->setRoles($roles);
		}

		protected function authenticateDatabase($password)
		{
			$sql = "select userID from users where username='{$this->getUsername()}' and password=md5('$password')";
			if (mysql_num_rows(mysql_query($sql))) { return true; }
			else { return false; }
		}


	}
?>
