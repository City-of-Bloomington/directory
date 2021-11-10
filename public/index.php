<?php
/**
 * @copyright 2012-2021 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
/**
 * Grab a timestamp for calculating process time
 */
declare (strict_types=1);
use Web\Authentication\Auth;

$startTime = microtime(true);

include '../src/Web/bootstrap.php';


$p     = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = $ROUTES->match($p, $_SERVER);
if ($route) {
    if (isset($route->params['controller'])) {
        $controller = $route->params['controller'];
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
                if (!empty($route->params['id'])) {
                        $_GET['id'] = $route->params['id'];
                    $_REQUEST['id'] = $route->params['id'];
                }

                $view = $c($route->params);
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
            $f = $ROUTES->getFailedRoute();
            $view = new \Web\Views\NotFoundView();
        }
    }
}
else {
    $f = $ROUTES->getFailedRoute();
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
