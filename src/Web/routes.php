<?php
/**
 * @copyright 2021-2024 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

$ROUTES = new Aura\Router\RouterContainer(BASE_URI);
$map    = $ROUTES->getMap();
$map->tokens([
    'id'       => '\d+',
    'username' => '[a-z\.]+'
]);



$map->get('home.index',    '/',       Web\People\Controllers\SearchController::class);
$map->get('login.login',   '/login',  Web\Authentication\LoginController::class);
$map->get('login.logout',  '/logout', Web\Authentication\LogoutController::class);

$map->attach('departments.', '/departments', function ($r) {
    $r->get('monkeys', '/monkeys', Web\Departments\Controllers\MonkeysController::class);
    $r->get('numbers', '/numbers', Web\Departments\Controllers\NumbersController::class);
    $r->get('view',    '{path}',   Web\Departments\Controllers\InfoController::class)
                                  ->tokens(['path' => '[a-z_/]+']);
    $r->get('index',   '',         Web\Departments\Controllers\ListController::class);
});

$map->attach('emergencyContacts.', '/emergency', function ($r) {
    $r->get('update', '/{username}/update', Web\EmergencyContacts\Controllers\UpdateController::class);
    $r->get('index',  '',                   Web\EmergencyContacts\Controllers\ListController::class);
});

$map->attach('people.', '/people', function ($r) {
    $r->get('search',      '/search'           , Web\People\Controllers\SearchController::class);
    $r->get('update',      '/{username}/update', Web\People\Controllers\UpdateController::class);
    $r->get('uploadPhoto', '/{username}/upload', Web\People\Controllers\UploadPhotoController::class);
    $r->get('photo',       '/{username}.jpg'   , Web\People\Controllers\PhotoController::class);
    $r->get('view',        '/{username}'       , Web\People\Controllers\InfoController::class);
});

$map->attach('users.', '/users', function ($r) {
    $r->get('update', '/update{/id}', Web\Users\Controllers\UpdateController::class);
    $r->get('delete', '/delete/{id}', Web\Users\Controllers\DeleteController::class);
    $r->get('view',   '/{id}'       , Web\Users\Controllers\InfoController::class);
    $r->get('index',  ''            , Web\Users\Controllers\ListController::class);
});

$map->attach('titles.', '/titles', function ($r) {
    $r->get('add',    '/add'        , Web\JobTitles\Controllers\AddController::class);
    $r->get('update', '/{id}/update', Web\JobTitles\Controllers\UpdateController::class);
    $r->get('index',  ''            , Web\JobTitles\Controllers\ListController::class);
});
