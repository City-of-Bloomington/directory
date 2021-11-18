<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

$rf = new \Aura\Router\RouterFactory(BASE_URI);
$ROUTES = $rf->newInstance();
$ROUTES->setTokens([
    'id'       => '\d+',
    'username' => '[a-z\.]+'
]);


$ROUTES->add('home.index',    '/'       )->setValues(['controller' => 'Web\Departments\Controllers\ListController']);
$ROUTES->add('login.login',   '/login'  )->setValues(['controller' => 'Web\Authentication\LoginController']);
$ROUTES->add('login.logout',  '/logout' )->setValues(['controller' => 'Web\Authentication\LogoutController']);

$ROUTES->attach('departments', '/departments', function ($r) {
    $r->add('numbers', '/numbers')->setValues(['controller'=>'Web\Departments\Controllers\NumbersController']);
    $r->add('view',    '{path}'  )->setValues(['controller' => 'Web\Departments\Controllers\InfoController'])
                                  ->addTokens(['path' => '[a-z_/]+']);
    $r->add('index',   ''        )->setValues(['controller' => 'Web\Departments\Controllers\ListController']);
});

$ROUTES->attach('people', '/people', function ($r) {
    $r->add('update',      '/{username}/update')->setValues(['controller' => 'Web\People\Controllers\UpdateController']);
    $r->add('uploadPhoto', '/{username}/upload')->setValues(['controller' => 'Web\People\Controllers\UploadPhotoController']);
    $r->add('photo',       '/{username}.jpg'   )->setValues(['controller' => 'Web\People\Controllers\PhotoController']);
    $r->add('view',        '/{username}'       )->setValues(['controller' => 'Web\People\Controllers\InfoController']);
});

$ROUTES->attach('users', '/users', function ($r) {
    $r->add('update', '/update{/id}')->setValues(['controller' => 'Web\Users\Controllers\UpdateController']);
    $r->add('delete', '/delete/{id}')->setValues(['controller' => 'Web\Users\Controllers\DeleteController']);
    $r->add('view',   '/{id}'       )->setValues(['controller' => 'Web\Users\Controllers\InfoController'  ]);
    $r->add('index',  ''            )->setValues(['controller' => 'Web\Users\Controllers\ListController'  ]);
});

$ROUTES->attach('titles', '/titles', function ($r) {
    $r->add('add',    '/add'        )->setValues(['controller' => 'Web\JobTitles\Controllers\AddController'   ]);
    $r->add('update', '/{id}/update')->setValues(['controller' => 'Web\JobTitles\Controllers\UpdateController']);
    $r->add('index',  ''            )->setValues(['controller' => 'Web\JobTitles\Controllers\ListController'  ]);
});
