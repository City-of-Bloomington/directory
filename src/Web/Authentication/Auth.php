<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Authentication;

use COB\HttpSignature\Context;
use Domain\Users\Entities\User;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\RequestInterface;

class Auth
{
    const HMAC_ALGORITHM       = 'hmac-sha256';
    const HEADER_AUTHORIZATION = 'Authorization';
    // Header containing the username that's attempting to make the request
    const HEADER_USERNAME      = 'username';

    public static function getAuthenticatedUser(AuthenticationService $service): ?User
    {
        if ( isset($_SESSION['USER'])) {
            return $_SESSION['USER'];
        }

        $request = ServerRequest::fromGlobals();
        if (   self::isHMACRequest       ($request)
            && self::isValidHMACSignature($request)) {

            $user = $service->identify($request->getHeader(self::HEADER_USERNAME)[0]);
            if ($user) { return $user; }
        }
        return null;
    }

    public static function isAuthorized(string $routeName, ?User $user): bool
    {
        global $ACL;

        list($resource, $permission) = explode('.', $routeName);
        $role = $user ? $user->role : 'Anonymous';

        return $ACL->hasResource($resource)
            && $ACL->isAllowed($role, $resource, $permission);
    }

    public static function isHMACRequest(RequestInterface $request): bool
    {
        return $request->hasHeader(self::HEADER_AUTHORIZATION)
            && $request->hasHeader(self::HEADER_USERNAME     );
    }

    public static function isValidHMACSignature(RequestInterface $request): bool
    {
        $keys = include SITE_HOME.'/api_keys.php';
        $context = new Context($keys);
        return $context->verify($request);
    }
}
