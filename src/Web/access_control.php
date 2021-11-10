<?php
/**
 * @copyright 2012-2021 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

use Web\Authentication\Auth;

use Laminas\Permissions\Acl\Acl;
use Laminas\Permissions\Acl\Role\GenericRole as Role;
use Laminas\Permissions\Acl\Role\RoleInterface;
use Laminas\Permissions\Acl\Resource\GenericResource as Resource;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Laminas\Permissions\Acl\Assertion\AssertionInterface;
$ACL = new Acl();
$ACL->addRole(new Role('Anonymous'))
    ->addRole(new Role('Public'   ), 'Anonymous')
    ->addRole(new Role('Staff'    ), 'Public'   )
    ->addRole(new Role('Administrator'));


/**
 * Create resources for all the routes
 */
foreach ($ROUTES->getRoutes() as $r) {
    list($resource, $permission) = explode('.', $r->name);
    if (!$ACL->hasResource($resource)) {
         $ACL->addResource(new Resource($resource));
    }
}

/**
 * Assign permissions to the resources
 */
$ACL->allow(null, ['home', 'login', 'departments']);

// Permissions for unauthenticated browsing
$ACL->allow(null, ['people'], ['index', 'view', 'photo', 'search']);

// Allow Staff to do stuff
// Staff need to be able to update their own Emergency Contact Information
class OwnInfoAssertion implements AssertionInterface
{
    public function assert(Acl $acl, RoleInterface $role=null, ResourceInterface $resource=null, $privilege=null)
    {
        if (isset($_REQUEST['username'])) {
            $user = Auth::getAuthenticatedUser();
            return $user->getUsername() == $_REQUEST['username'];
        }
        return false;
    }
}
$ACL->allow('Staff', 'people', 'updateEmergencyContacts', new OwnInfoAssertion());

// Administrator is allowed access to everything
$ACL->allow('Administrator');
