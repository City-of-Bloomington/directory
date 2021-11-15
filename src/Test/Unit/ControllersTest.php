<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
use PHPUnit\Framework\TestCase;

class ControllersTest extends TestCase
{
    protected static $container;

    public static function setUpBeforeClass(): void
    {
        global $DI;
        self::$container = $DI;
    }

    public function controllers(): array
    {
        $pattern  = APPLICATION_HOME.'/src/Web/{*,*/**}/*Controller.php';
        $files    = [];
        foreach (glob($pattern, GLOB_BRACE) as $f) {
            $f = str_replace(APPLICATION_HOME.'/src/', '',  $f);
            $f = str_replace('/',     '\\', $f);
            $f = str_replace('.php',   '',  $f);
            $files[] = [$f];
        }
        return $files;
    }

	/**
	 * @dataProvider controllers
	 */
    public function testConstructors($class)
    {
        $c = new $class(self::$container);
        $this->assertEquals($class, get_class($c));
    }
}
