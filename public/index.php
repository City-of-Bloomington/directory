<?php
/**
 * @copyright 2012-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Application\Controllers;

use Application\Authentication\Auth;
use Application\Models\DepartmentGateway;
use Blossom\Classes\Template;
use Blossom\Classes\Block;

/**
 * Grab a timestamp for calculating process time
 */
$startTime = microtime(1);

include '../bootstrap.php';

// Check for routes
if (preg_match('|'.BASE_URI.'/photos/(.+)\.jpg|', $_SERVER['REQUEST_URI'], $matches)) {
    $resource         = 'people';
    $action           = 'photo';
    $_GET['username'] = $matches[1];
}
elseif (preg_match('|'.BASE_URI.'(/([a-zA-Z0-9]+))?(/([a-zA-Z0-9]+))?|',$_SERVER['REQUEST_URI'],$matches)) {
	$resource = isset($matches[2]) ? $matches[2] : 'index';
	$action   = isset($matches[4]) ? $matches[4] : 'index';
}

// Create the default Template
$template = !empty($_REQUEST['format'])
	? new Template('default',$_REQUEST['format'])
	: new Template('default');

// Execute the Controller::action()
if (isset($resource) && isset($action) && $ZEND_ACL->hasResource($resource)) {
    if (Auth::isAuthorized($resource, $action, Auth::getAuthenticatedUser())) {
		$controller = __namespace__.'\\'.ucfirst($resource).'Controller';
		$c = new $controller($template);
		$c->$action();
	}
	else {
		header('HTTP/1.1 403 Forbidden', true, 403);
		$_SESSION['errorMessages'][] = new \Exception('noAccessAllowed');
	}
}
else {
    // Treat the url as if it's a path to a department
    if (preg_match('|'.BASE_URI.'([^\?]+)|', $_SERVER['REQUEST_URI'], $matches)
            && !empty($matches[1])) {
        $_GET['dn'] = urldecode(DepartmentGateway::getDnForPath($matches[1]));
        $c = new DepartmentsController($template);
        $c->view();
    }
    else {
        header('HTTP/1.1 404 Not Found', true, 404);
        $template->blocks[] = new Block('404.inc');
    }
}

echo $template->render();

if ($template->outputFormat === 'html') {
    # Calculate the process time
    $endTime = microtime(1);
    $processTime = $endTime - $startTime;
    echo "<!-- Process Time: $processTime -->";
}
