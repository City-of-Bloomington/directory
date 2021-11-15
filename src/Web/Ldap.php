<?php
/**
 * An LDAP Connection class
 *
 * @copyright 2014-2021 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Web;
/**
 * Uncomment this line to have more debug information go
 * into the apache error log.
 */
#ldap_set_option(null, LDAP_OPT_DEBUG_LEVEL, 7);

abstract class Ldap
{
    const NETWORK_TIMEOUT = 3;

    private   $config;
    protected $connection;

    public function __construct($config)
    {
        if ($this->connection = ldap_connect($config['server'])) {
            ldap_set_option($this->connection, LDAP_OPT_PROTOCOL_VERSION,3);
            ldap_set_option($this->connection, LDAP_OPT_REFERRALS, 0);
            ldap_set_option($this->connection, LDAP_OPT_TIMELIMIT,       self::NETWORK_TIMEOUT);
            ldap_set_option($this->connection, LDAP_OPT_NETWORK_TIMEOUT, self::NETWORK_TIMEOUT);
            if (!empty($config['admin_binding'])) {
                if (!ldap_bind(
                        $this->connection,
                        $config['admin_binding'],
                        $config['admin_pass']
                    )) {
                    throw new \Exception(ldap_error($this->connection));
                }
            }
            else {
                if (!ldap_bind($this->connection)) {
                    throw new \Exception(ldap_error($this->connection));
                }
            }
            $this->config = $config;
        }
        else {
            throw new \Exception(ldap_error($this->connection));
        }
    }

    protected function getConfig(): array { return $this->config; }
}
