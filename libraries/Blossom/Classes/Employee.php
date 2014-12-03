<?php
/**
 * A class for working with entries in LDAP.
 *
 * This class is written specifically for the City of Bloomington's
 * LDAP layout.  If you are going to be doing LDAP authentication
 * with your own LDAP server, you will probably need to customize
 * the fields used in this class.
 *
 * @copyright 2011-2013 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Blossom\Classes;

/**
 * Uncomment this line to have more debug information go
 * into the apache error log.
 */
#ldap_set_option(null, LDAP_OPT_DEBUG_LEVEL, 7);

class Employee implements ExternalIdentity
{
	private static $connection;
	private $config;
	private $entry;

	/**
	 * @param array $config
	 * @param string $username
	 * @param string $password
	 * @throws Exception
	 */
	public static function authenticate($username,$password)
	{
		global $DIRECTORY_CONFIG;
		return Ldap::authenticate($DIRECTORY_CONFIG['Employee'], $username, $password);
	}


	/**
	 * Loads an entry from the LDAP server for the given user
	 *
	 * @param array $config
	 * @param string $username
	 */
	public function __construct($username)
	{
		global $DIRECTORY_CONFIG;
		$this->config = $DIRECTORY_CONFIG['Employee'];
		
		$connection = Ldap::getConnection($this->config);

		$result = ldap_search(
			$connection,
			$this->config['DIRECTORY_BASE_DN'],
			$this->config['DIRECTORY_USERNAME_ATTRIBUTE']."=$username"
		);
		if (ldap_count_entries($connection,$result)) {
			$entries = ldap_get_entries($connection, $result);
			$this->entry = $entries[0];
		}
		else {
			throw new \Exception('ldap/unknownUser');
		}
	}

	/**
	 * @return string
	 */
	public function getUsername()	{ return $this->get('cn'); }
	public function getFirstname()	{ return $this->get('givenname'); }
	public function getLastname()	{ return $this->get('sn'); }
	public function getEmail()		{ return $this->get('mail'); }
	public function getPhone()		{ return $this->get('telephonenumber'); }
	public function getAddress()	{ return $this->get('postaladdress'); }
	public function getCity()		{ return $this->get('l'); }
	public function getState()		{ return $this->get('st'); }
	public function getZip()		{ return $this->get('postalcode'); }

	/**
	 * Returns the first scalar value from the entry's field
	 *
	 * @param string $field
	 * @return string
	 */
	private function get($field) {
		return isset($this->entry[$field][0]) ? $this->entry[$field][0] : '';
	}
}
