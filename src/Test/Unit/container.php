<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
use Aura\Di\ContainerBuilder;

$builder = new ContainerBuilder();
$DI = $builder->newInstance();

//---------------------------------------------------------
// Services
//---------------------------------------------------------
$DI->set(    'Web\Authentication\AuthenticationService',
$DI->lazyNew('Test\DataStorage\StubAuthenticationService'));
