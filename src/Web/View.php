<?php
/**
 * @copyright 2006-2021 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Web;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;
use Twig\TwigTest;

abstract class View
{
    protected $vars;
    protected $twig;
    public    $outputFormat = 'html';

	abstract public function render(): string;

	/**
	 * Configures the gettext translations
	 */
	public function __construct()
	{
        $this->outputFormat = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';

        $tpl = [];
        if (defined('THEME')) {
            if (is_dir ( SITE_HOME.'/Themes/'.THEME.'/templates')) {
                $tpl[] = SITE_HOME.'/Themes/'.THEME.'/templates';
            }
        }
        $tpl[]      = APPLICATION_HOME.'/templates';
        $loader     = new FilesystemLoader($tpl);
        $this->twig = new Environment($loader, [ 'cache'            => false,
                                                 'strict_variables' => true,
                                                 'debug'            => true]);
        $this->twig->addGlobal('APPLICATION_NAME', APPLICATION_NAME);
        $this->twig->addGlobal('VERSION',          VERSION);
        $this->twig->addGlobal('BASE_URL',         BASE_URL);
        $this->twig->addGlobal('BASE_URI',         BASE_URI);
        $this->twig->addGlobal('REQUEST_URI',      $_SERVER['REQUEST_URI']);
        if (isset($_SESSION['USER'])) {
            $this->twig->addGlobal('USER', $_SESSION['USER']);
        }

        $this->twig->addFunction(new TwigFunction('_'  ,         [$this, 'translate'  ]));
        $this->twig->addFunction(new TwigFunction('uri',         [$this, 'generateUri']));
        $this->twig->addFunction(new TwigFunction('url',         [$this, 'generateUrl']));
        $this->twig->addFunction(new TwigFunction('isAllowed',   [$this, 'isAllowed'  ]));
        $this->twig->addFunction(new TwigFunction('current_url', [$this, 'current_url']));

        $locale = LOCALE.'.utf8';
        $this->twig->addGlobal('LANG', strtolower(substr(LOCALE, 0, 2)));
        putenv("LC_ALL=$locale");
        setlocale(LC_ALL, $locale);
        bindtextdomain('labels',   APPLICATION_HOME.'/language');
        bindtextdomain('messages', APPLICATION_HOME.'/language');
        bindtextdomain('errors',   APPLICATION_HOME.'/language');
        textdomain('labels');
	}

	/**
	 * Cleans strings for output
	 *
	 * There are more bad characters than htmlspecialchars deals with.  We just want
	 * to add in some other characters to clean.  While here, we might as well
	 * have it trim out the whitespace too.
	 *
	 * @param  array|string $input
	 * @param  int          $quotes Optional, the desired constant to use for the htmlspecidalchars call
	 * @return string
	 */
	public static function escape($input, $quotes=ENT_QUOTES)
	{
		if (is_array($input)) {
			foreach ($input as $key=>$value) {
				$input[$key] = self::escape($value,$quotes);
			}
		}
		else {
			$input = htmlspecialchars(trim($input), $quotes, 'UTF-8');
		}

		return $input;
	}

	/**
	 * Reverses the escaping done by View::escape()
	 *
	 * @param array|string $input
	 * @return string
	 */
	public static function unescape($input)
	{
        if (is_array($input)) {
            foreach ($input as $key=>$value) {
                $input[$key] = self::unescape($value);
            }
        }
        else {
            $input = htmlspecialchars_decode(trim($input), ENT_QUOTES);
        }
        return $input;
	}

    /**
     * Returns the gettext translation of msgid
     *
     * The default domain is "labels".  Any other text domains must be passed
     * in the second parameter.
     *
     * For entries in the PO that are plurals, you must pass msgid as an array
     * $this->translate( ['msgid', 'msgid_plural', $num] )
     *
     * @param mixed $msgid String or Array
     * @param string $domain Alternate domain
     * @return string
     */
    public function translate($msgid, $domain=null)
    {
        if (is_array($msgid)) {
            return $domain
                ? dngettext($domain, $msgid[0], $msgid[1], $msgid[2])
                : ngettext (         $msgid[0], $msgid[1], $msgid[2]);
        }
        else {
            return $domain
                ? dgettext($domain, $msgid)
                : gettext (         $msgid);
        }
    }

    /**
     * Alias of $this->translate()
     */
    public function _($msgid, $domain=null)
    {
        return $this->translate($msgid, $domain);
    }

    public static $supportedDateFormatStrings = [
        'm', 'n', 'd', 'j', 'Y', 'H', 'g', 'i', 's', 'a'
    ];

    /**
     * Converts the PHP date format string syntax into something for humans
     *
     * @param string $format
     * @return string
     */
    public static function translateDateString($format)
    {
        return str_replace(
            self::$supportedDateFormatStrings,
            ['mm', 'mm', 'dd', 'dd', 'yyyy', 'hh', 'hh', 'mm', 'ss', 'am'],
            $format
        );
    }

    public static function convertDateFormat($format, $syntax)
    {
        $languages = [
            'mysql'  => ['%m', '%c', '%d', '%e', '%Y', '%H', '%l', '%i', '%s', '%p'],
            'jquery' => ['mm', 'm',  'dd', 'd',  'yy', 'HH', 'h',  'mm', 'ss', 'a' ]
        ];

        if (array_key_exists($syntax, $languages)) {
            return str_replace(
                self::$supportedDateFormatStrings,
                $languages[$syntax],
                $format
            );
        }
    }

    /**
     * Creates a URI for a named route
     *
     * This imports the $ROUTES global variable and calls the
     * generate function on it.
     *
     * @see https://github.com/auraphp/Aura.Router/tree/2.x
     */
    public static function generateUri($route_name, $params=[]): string
    {
        global $ROUTES;
        return $ROUTES->generateRaw($route_name, $params);
    }

    public static function generateUrl($route_name, $params=[]): string
    {
        return "https://".BASE_HOST.self::generateUri($route_name, $params);
    }

    public static function current_url(): Url
    {
        return new Url(Url::current_url(BASE_HOST));
    }

	public static function isAllowed(string $resource, ?string $action=null): bool
    {
		global $ACL;
		$role = 'Anonymous';
		if (isset  ($_SESSION['USER']) && $_SESSION['USER']->role) {
			$role = $_SESSION['USER']->role;
		}
		return $ACL->isAllowed($role, $resource, $action);
    }
}
