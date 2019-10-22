<?php
/**
 * Where on the filesystem this application is installed
 */
define('APPLICATION_HOME', realpath(__DIR__.'/../../..'));
define('BLOSSOM', APPLICATION_HOME.'/vendor/City-of-Bloomington/blossom-lib');
define('VERSION', trim(file_get_contents(APPLICATION_HOME.'/VERSION')));

define('SITE_HOME', __DIR__);
include SITE_HOME.'/test_config.inc';

$loader = require APPLICATION_HOME.'/vendor/autoload.php';
$loader->addPsr4('Site\\', SITE_HOME);
include APPLICATION_HOME.'/access_control.inc';
