<?php
/**
 * Where on the filesystem this application is installed
 */
define('APPLICATION_HOME', __DIR__);
define('BLOSSOM', APPLICATION_HOME.'/vendor/City-of-Bloomington/blossom-lib');

/**
 * Multi-Site support
 *
 * To allow multiple sites to use this same install base,
 * define the SITE_HOME variable in the Apache config for each
 * site you want to host.
 *
 * SITE_HOME is the directory where all site-specific data and
 * configuration are stored.  For backup purposes, backing up this
 * directory would be sufficient for an easy full restore.
 */
define('SITE_HOME', !empty($_SERVER['SITE_HOME']) ? $_SERVER['SITE_HOME'] : __DIR__.'/data');

/**
 * Enable autoloading for the PHP libraries
 */
require_once APPLICATION_HOME.'/vendor/zendframework/zend-loader/src/AutoloaderFactory.php';
$config = [
	'Zend\Loader\StandardAutoloader' => [
		'namespaces' => [
			'Application' => APPLICATION_HOME,
			'Site'        => SITE_HOME,
            'Blossom'     => BLOSSOM.'/src',
            #'Aura\Router'          => APPLICATION_HOME.'/vendor/aura/router/src',
            'Zend\Stdlib'          => APPLICATION_HOME.'/vendor/zendframework/zend-stdlib/src',
            'Zend\Permissions\Acl' => APPLICATION_HOME.'/vendor/zendframework/zend-permissions-acl/src',
            'Zend\Paginator'       => APPLICATION_HOME.'/vendor/zendframework/zend-paginator/src',
            'Zend\Log'             => APPLICATION_HOME.'/vendor/zendframework/zend-log/src',
            'Zend\Loader'          => APPLICATION_HOME.'/vendor/zendframework/zend-loader/src',
            'Zend\Hydrator'        => APPLICATION_HOME.'/vendor/zendframework/zend-hydrator/src',
            'Zend\Db'              => APPLICATION_HOME.'/vendor/zendframework/zend-db/src',
		]
	]
];
Zend\Loader\AutoloaderFactory::factory($config);

include SITE_HOME.'/site_config.inc';
#include APPLICATION_HOME.'/routes.inc';
include APPLICATION_HOME.'/access_control.inc';

/**
 * Session Startup
 * Don't start a session for CLI usage.
 * We only want sessions when PHP code is executed from the webserver
 */
if (!defined('STDIN')) {
	ini_set('session.save_path', SITE_HOME.'/sessions');
	ini_set('session.cookie_path', BASE_URI);
	session_start();
}