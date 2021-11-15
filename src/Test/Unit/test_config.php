<?php
define('APPLICATION_NAME','directory');

define('BASE_URI' , '/directory');
define('BASE_HOST', 'localhost');
define('BASE_URL' , "http://".BASE_HOST.BASE_URI);

$LDAP = [
    'Employee' => [
        'restricted'   => 'CN=Restricted Group Name',
        'promoted'     => 'CN=Public Group Name',
        'internal_ip'  => '^10\.(20|50)\.'
    ]
];

define('DATE_FORMAT',    'n/j/Y');
define('TIME_FORMAT',    'g:i a');
define('DATETIME_FORMAT', DATE_FORMAT.' '.TIME_FORMAT);
define('LOCALE', 'en_US');
