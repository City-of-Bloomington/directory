<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
use Aura\Di\ContainerBuilder;

$builder = new ContainerBuilder();
$DI = $builder->newInstance();

$DI->set('db.default', \Web\Database::getConnection('default', $DATABASES['default']));
#$DI->set('db.hr',      \Web\Database::getConnection('hr',      $DATABASES['hr'     ]));


//---------------------------------------------------------
// Declare database repositories
//---------------------------------------------------------
$DI->params[ 'Web\Departments\LdapDepartmentGateway']['config'] = $LDAP['Employee'];
$DI->set( 'Domain\Departments\DataStorage\DepartmentsGateway',
$DI->lazyNew('Web\Departments\LdapDepartmentGateway'));

$repos = [
    'Users'
];
foreach ($repos as $t) {
    $DI->params[ "Web\\$t\\Pdo{$t}Repository"]["pdo"] = $DI->lazyGet('db.default');
    $DI->set("Domain\\$t\\DataStorage\\{$t}Repository",
    $DI->lazyNew("Web\\$t\\Pdo{$t}Repository"));
}

//---------------------------------------------------------
// Metadata providers
//---------------------------------------------------------

//---------------------------------------------------------
// Services
//---------------------------------------------------------
$DI->params[ 'Web\Authentication\AuthenticationService']['repository'] = $DI->lazyGet('Domain\Users\DataStorage\UsersRepository');
$DI->params[ 'Web\Authentication\AuthenticationService']['config'    ] = $LDAP;
$DI->set(    'Web\Authentication\AuthenticationService',
$DI->lazyNew('Web\Authentication\AuthenticationService'));

//---------------------------------------------------------
// Actions
//---------------------------------------------------------

// Departments
foreach (['Info', 'Search'] as $a) {
    $DI->params[ "Domain\\Departments\\Actions\\$a\\Command"]['gateway'] = $DI->lazyGet('Domain\Departments\DataStorage\DepartmentsGateway');
    $DI->set(    "Domain\\Departments\\Actions\\$a\\Command",
    $DI->lazyNew("Domain\\Departments\\Actions\\$a\\Command"));
}

// People
foreach (['Info', 'SavePhoto', 'Update'] as $a) {
    $DI->params[ "Domain\\People\\Actions\\$a\\Command"]['gateway'] = $DI->lazyGet('Domain\Departments\DataStorage\DepartmentsGateway');
    $DI->set(    "Domain\\People\\Actions\\$a\\Command",
    $DI->lazyNew("Domain\\People\\Actions\\$a\\Command"));
}

// Users
foreach (['Delete', 'Info', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\Users\\Actions\\$a\\Command"]["repository"] = $DI->lazyGet('Domain\Users\DataStorage\UsersRepository');
    $DI->set(    "Domain\\Users\\Actions\\$a\\Command",
    $DI->lazyNew("Domain\\Users\\Actions\\$a\\Command"));
}
$DI->params['Domain\Users\Actions\Update\Command']['auth'] = $DI->lazyGet('Web\Authentication\AuthenticationService');
