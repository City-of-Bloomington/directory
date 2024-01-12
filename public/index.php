<?php
/**
 * @copyright 2012-2024 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
/**
 * Grab a timestamp for calculating process time
 */
declare (strict_types=1);
use Web\Authentication\Auth;

$startTime = microtime(true);

include '../src/Web/bootstrap.php';
ini_set('session.save_path', SITE_HOME.'/sessions');
ini_set('session.cookie_path', BASE_URI);
session_start();

$matcher = $ROUTES->getMatcher();
$route   = $matcher->match(GuzzleHttp\Psr7\ServerRequest::fromGlobals());

if ($route) {
    $controller = $route->handler;
    $c = new $controller($DI);
    if (is_callable($c)) {
        $user = Auth::getAuthenticatedUser($DI->get('Web\Authentication\AuthenticationService'));
        if (Auth::isAuthorized($route->name, $user)) {
            // Convenience:
            // Most of our applications are just basic form processing.
            // Thus, the controllers typically read directly from the PHP
            // global SERVER variables.
            //
            // 'id' is the standard name for primary key in tables.
            // Most routes, by default, allow for a fancy treatment of {id} in the URL.
            // If it the id param comes from the route handling, we copy
            // it to the PHP Server variables, so we don't have to have
            // special parameter handling code for the common case of checking
            // for an id parameter.
            if (!empty($route->attributes['id'])) {
                $_GET['id'] = $route->attributes['id'];
                $_REQUEST['id'] = $route->attributes['id'];
            }

            $view = $c($route->attributes);
        }
        else {
            if     ( isset($_SESSION['USER'])
                || (!empty($_REQUEST['format']) && $_REQUEST['format'] != 'html')) {
                $view = new \Web\Views\ForbiddenView();
                }
                else {
                    header('Location: '.\Web\View::generateUrl('login.login'));
                    exit();
                }
        }
    }
    else {
        $f = $matcher->getFailedRoute();
        $view = new \Web\Views\NotFoundView();
    }
}
else {
    $f = $matcher->getFailedRoute();
    $view = new \Web\Views\NotFoundView();
}

echo $view->render();

// Append some useful stats to the output of HTML pages
if ($view->outputFormat === 'html') {
    # Calculate the process time
    $endTime = microtime(true);
    $processTime = $endTime - $startTime;
    echo "<!-- Process Time: $processTime -->\n";

    $size   = ['B','kB','MB','GB','TB','PB','EB','ZB','YB'];
    $bytes  = memory_get_peak_usage();
    $factor = floor( (strlen("$bytes") - 1) / 3);
    $memory = sprintf("%.2f", $bytes / pow(1024, $factor)) . @$size[$factor];
    echo "<!-- Memory: $memory -->";
}
