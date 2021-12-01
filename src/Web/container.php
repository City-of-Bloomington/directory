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
$DI->set('db.hr',      \Web\Database::getConnection('hr',      $DATABASES['hr'     ]));

//---------------------------------------------------------
// Declare database repositories
//---------------------------------------------------------
$DI->params[ "Web\EmergencyContacts\PdoEmergencyContactsRepository"]["pdo"] = $DI->lazyGet('db.default');
$DI->set( "Domain\EmergencyContacts\Repository",
$DI->lazyNew("Web\EmergencyContacts\PdoEmergencyContactsRepository"));

$DI->params[ 'Web\Departments\LdapDepartmentGateway']['config'] = $LDAP['Employee'];
$DI->set( 'Domain\Departments\DataStorage\DepartmentsGateway',
$DI->lazyNew('Web\Departments\LdapDepartmentGateway'));

$DI->params[ 'Web\JobTitles\PdoJobTitlesRepository']['pdo'] = $DI->lazyGet('db.hr');
$DI->set( 'Domain\JobTitles\DataStorage\JobTitlesRepository',
$DI->lazyNew('Web\JobTitles\PdoJobTitlesRepository'));

$DI->params[ "Web\Users\PdoUsersRepository"]["pdo"] = $DI->lazyGet('db.default');
$DI->set( "Domain\Users\DataStorage\UsersRepository",
$DI->lazyNew("Web\Users\PdoUsersRepository"));

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

// Emergency Contacts
foreach (['Find', 'Load'] as $a) {
    $DI->params[ "Domain\\EmergencyContacts\\Actions\\$a\\Command"]["repository"] = $DI->lazyGet('Domain\EmergencyContacts\Repository');
    $DI->set(    "Domain\\EmergencyContacts\\Actions\\$a\\Command",
    $DI->lazyNew("Domain\\EmergencyContacts\\Actions\\$a\\Command"));
}

// Departments
foreach (['Info', 'Search'] as $a) {
    $DI->params[ "Domain\\Departments\\Actions\\$a\\Command"]['gateway'] = $DI->lazyGet('Domain\Departments\DataStorage\DepartmentsGateway');
    $DI->set(    "Domain\\Departments\\Actions\\$a\\Command",
    $DI->lazyNew("Domain\\Departments\\Actions\\$a\\Command"));
}
// Job Titles
foreach (['Add', 'Find', 'Info', 'Update'] as $a) {
    $DI->params[ "Domain\\JobTitles\\Actions\\$a\\Command"]["repository"] = $DI->lazyGet('Domain\JobTitles\DataStorage\JobTitlesRepository');
    $DI->set(    "Domain\\JobTitles\\Actions\\$a\\Command",
    $DI->lazyNew("Domain\\JobTitles\\Actions\\$a\\Command"));
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
