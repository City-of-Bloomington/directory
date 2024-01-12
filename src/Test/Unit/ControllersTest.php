<?php
/**
 * @copyright 2019-2023 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
use PHPUnit\Framework\TestCase;
use Aura\Di\ContainerBuilder;

class ControllersTest extends TestCase
{
    protected static $container;

    public static function setUpBeforeClass(): void
    {
        $builder = new ContainerBuilder();
        self::$container = $builder->newInstance();
        self::$container->set('Web\Authentication\AuthenticationService',
        self::$container->lazyNew('Test\DataStorage\StubAuthenticationService'));
    }

    public static function controllers(): array
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
