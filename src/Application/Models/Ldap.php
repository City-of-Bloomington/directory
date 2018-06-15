<?php
/**
 * @copyright 2014-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;
/**
 * Uncomment this line to have more debug information go
 * into the apache error log.
 */
#ldap_set_option(null, LDAP_OPT_DEBUG_LEVEL, 7);

class Ldap
{
    const NETWORK_TIMEOUT = 3;

    private static $connection;

    public static function authenticate($config, $user, $pass)
    {
        $bindUser = sprintf(str_replace('{username}', '%s', $config['DIRECTORY_USER_BINDING']), $user);

        $connection = ldap_connect($config['DIRECTORY_SERVER']) or die("Couldn't connect to LDAP");
        ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
        if (ldap_bind($connection,$bindUser,$pass)) {
            return true;
        }
        return false;
    }

    public static function getConnection($config)
    {
        if (!self::$connection) {
            if (self::$connection = ldap_connect($config['DIRECTORY_SERVER'])) {
                ldap_set_option(self::$connection, LDAP_OPT_PROTOCOL_VERSION,3);
                ldap_set_option(self::$connection, LDAP_OPT_REFERRALS, 0);
                ldap_set_option(self::$connection, LDAP_OPT_TIMELIMIT,       self::NETWORK_TIMEOUT);
                ldap_set_option(self::$connection, LDAP_OPT_NETWORK_TIMEOUT, self::NETWORK_TIMEOUT);
                if (!empty($config['DIRECTORY_ADMIN_BINDING'])) {
                    if (!ldap_bind(
                            self::$connection,
                            $config['DIRECTORY_ADMIN_BINDING'],
                            $config['DIRECTORY_ADMIN_PASS']
                        )) {
                        throw new \Exception(ldap_error(self::$connection));
                    }
                }
                else {
                    if (!ldap_bind(self::$connection)) {
                        throw new \Exception(ldap_error(self::$connection));
                    }
                }
                return self::$connection;
            }
            else {
                throw new \Exception(ldap_error(self::$connection));
            }
        }
    }
}
