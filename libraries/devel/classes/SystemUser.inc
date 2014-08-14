<?php
/*
*	Handles authentication and password handling for all city LDAP people.
*	Applications should extend this class for their own users.  That way,
*	a city employee will have the same username and password on all applications.
* 	Applications should use these public functions for their own users.
*/
	abstract class SystemUser extends ActiveRecord
	{
		public function authenticate($password)
		{
			switch($this->getAuthenticationMethod())
			{
				case "LDAP":
					if ($this->authenticateLDAP($password)) { return true; }
					else { return false; }
				break;

				case "local":
					if ($this->authenticateDatabase($password)) { return true; }
					else { return false; }
				break;
			}
		}

		public function startNewSession()
		{
			session_destroy();
			session_start();

			$_SESSION['USER'] = $this;
			$_SESSION['IP_ADDRESS'] = $_SERVER['REMOTE_ADDR'];
			$_SESSION['APPLICATION_NAME'] = APPLICATION_NAME;
		}

		public function savePassword()
		{
			switch($this->getAuthenticationMethod())
			{
				case "LDAP":
					$this->saveLDAPPassword();
				break;

				case "local":
					$this->saveLocalPassword();
				break;
			}
		}

		private function connectADLDAP() 
		{
			$connection_spec = array(
				'base_dn'		=> ADS_DN,
				'account_suffix'	=> ADS_ACCT_SUFFIX,
				'admin_username'	=> ADS_ADMIN_USER,
				'admin_password'	=> ADS_ADMIN_PASS,
				'domain_controllers'	=> array(ADS_SERVER),
				'ad_port'		=> 389,
				'use_ssl'		=> 0
			);	

			try {
				$adldap = new adLDAP($connection_spec);
			}

			catch (adLDAPException $e) {
				echo $e;
				exit();
			}
			return $adldap;
		}

		private function authenticateLDAP($password)
		{
			$adldap = $this->connectADLDAP();
			$username = $this->getUsername();
			$result = $adldap->authenticate($username, $password);
		
			if ($result) { return true; }
			else { return false; }
		}

		private function saveLDAPPassword()
		{
			$connection = ldap_connect(LDAP_SERVER);
			ldap_set_option($connection,LDAP_OPT_PROTOCOL_VERSION,3);
			ldap_bind($connection,LDAP_USERNAME_ATTRIBUTE."=".LDAP_ADMIN_USER.",o=".LDAP_DOMAIN,LDAP_ADMIN_PASS) or die(ldap_error($connection));

			$result = ldap_search($connection,LDAP_DN,LDAP_USERNAME_ATTRIBUTE."={$this->getUsername()}");
			$entries = ldap_get_entries($connection, $result);

			$dn = LDAP_USERNAME_ATTRIBUTE."={$this->getUsername()},ou=people,o=".LDAP_DOMAIN;
			if ($this->getPassword())
			{

				$salt = substr(md5(time()),0,2);
				$encryptedPassword = "{CRYPT}".crypt($this->getPassword(),$salt);

				$password = array(LDAP_PASSWORD_ATTRIBUTE=>$encryptedPassword);

				if (isset($entries[0][LDAP_PASSWORD_ATTRIBUTE]))
				{
					# Modify
					ldap_mod_replace($connection,$dn,$password) or die(print_r($password).ldap_error($connection));
				}
				else
				{
					# Add
					ldap_mod_add($connection,$dn,$password) or die(print_r($password).ldap_error($connection));
				}
			}
			else
			{
				# Delete
				$password = array();
				ldap_mod_del($connection,$dn,$password) or die(print_r($password).ldap_error($connection));
			}
		}



		/*
		* All applications will have to implement users. They will at least need these fields
		*/
		abstract public function getId();
		abstract public function getUsername();
		abstract public function getPassword();
		abstract public function getAuthenticationMethod();
		abstract public function getRoles();

		abstract public function hasRole($roles);

		abstract public function setAuthenticationMethod($method);
		abstract public function setRoles($roles);
		abstract public function setUsername($username);

		/*
		* Passwords are set in clear text.  The only times you would want to set a password
		* is when you're adding a new password or changing a person's password.
		* Either way, it's up to the individual save routines to handle encrypting the new password
		* before storing it.  Passwords should not be loaded in the constructor - they're
		* supposed to be encrypted, so what's the point?
		*/
		abstract public function setPassword($password);

		/*
		*	Used to hand authentication off to the application
		*/
		abstract protected function authenticateDatabase($password);

		/*
		* 	Used to hand password saving off to the application
		*/
		abstract protected function saveLocalPassword();
	}
?>
