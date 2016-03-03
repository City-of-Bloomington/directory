<?php
/**
 * @copyright 2012-2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Models;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Assertion\AssertionInterface;

$ZEND_ACL = new Acl();
$ZEND_ACL->addRole(new Role('Anonymous'))
		 ->addRole(new Role('Public'), 'Anonymous')
		 ->addRole(new Role('Staff'), 'Public')
		 ->addRole(new Role('Administrator'), 'Staff');

/**
 * Declare all the resources
 */
$ZEND_ACL->addResource(new Resource('index'));
$ZEND_ACL->addResource(new Resource('people'));
$ZEND_ACL->addResource(new Resource('users'));
$ZEND_ACL->addResource(new Resource('login'));

$ZEND_ACL->addResource(new Resource('departments'));
$ZEND_ACL->addResource(new Resource('emergencyContacts'));
$ZEND_ACL->addResource(new Resource('synchronize'));

/**
 * Assign permissions to the resources
 */
$ZEND_ACL->allow(null,['login', 'index', 'departments']);

// Permissions for unauthenticated browsing
$ZEND_ACL->allow(null,
				['people'],
				['index', 'view', 'photo', 'search']);

// Allow Staff to do stuff
// Staff need to be able to update their own Emergency Contact Information
class OwnInfoAssertion implements AssertionInterface
{
    public function assert(Acl $acl, RoleInterface $role=null, ResourceInterface $resource=null, $privilege=null)
    {
        return (   isset($_REQUEST['username'])
                && isset($_SESSION['USER'])
                && $_SESSION['USER']->getUsername()==$_REQUEST['username']
        );
    }
}
$ZEND_ACL->allow('Staff', 'people', 'updateEmergencyContacts', new OwnInfoAssertion());

// Administrator is allowed access to everything
$ZEND_ACL->allow('Administrator');
