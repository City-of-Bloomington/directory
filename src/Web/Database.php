<?php
/**
 * @copyright 2006-2024 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web;

class Database
{
    private static $connections = [];

    public static function getConnection(array $config, string $name='default', bool $reconnect=false): \PDO
    {
        if ($reconnect) {
            if (isset(self::$connections[$name])) {
                unset(self::$connections[$name]);
            }
        }

        if (!isset(self::$connections[$name])) {
            try {
                self::$connections[$name] = new \PDO($config['dsn'], $config['user'], $config['pass'], $config['opts'] ?? null);
                self::$connections[$name]->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            }
            catch (\Exception $e) {
                print_r($e);
                die("Could not connect to $name database\n");
            }
        }
        return self::$connections[$name];
    }
}
