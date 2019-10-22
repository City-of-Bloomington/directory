<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Application\Authentication;

use Aws\Credentials\Credentials;
use Aws\Signature\SignatureV4;
use Application\Models\User;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\RequestInterface;

class Auth
{
    const HEADER_AUTHORIZATION = 'Authorization';
    const HEADER_KEY_NAME      = 'AccessKeyId';
    // Header containing the username that's attempting to make the request
    const HEADER_USERNAME      = 'AccountTrackerUsername';

    public static function getAuthenticatedUser(): ?User
    {
        static $user = null;
        if (!$user) {
            if (  isset($_SESSION['USER'])) {
                $user = $_SESSION['USER'];
            }

            $request = ServerRequest::fromGlobals();
            if (   self::isHMACRequest       ($request)
                && self::isValidHMACSignature($request)) {

                try {
                    $user = new User($request->getHeader(self::HEADER_USERNAME)[0]);
                }
                catch (\Exception $e) {  }
            }
        }
        return $user;
    }

    public static function isAuthorized(string $resource, ?string $action=null, ?User $user=null): bool
    {
        global $ZEND_ACL;

        $role = $user ? $user->getRole() : 'Anonymous';

        return $ZEND_ACL->hasResource($resource)
            && $ZEND_ACL->isAllowed($role, $resource, $action);
    }

    public static function isHMACRequest(RequestInterface $request): bool
    {
        return $request->hasHeader(self::HEADER_AUTHORIZATION)
            && $request->hasHeader(self::HEADER_KEY_NAME     )
            && $request->hasHeader(self::HEADER_USERNAME     )
            && substr($request->getHeader(self::HEADER_AUTHORIZATION)[0], 0, 16) == 'AWS4-HMAC-SHA256';
    }

    public static function isValidHMACSignature(RequestInterface $request): bool
    {
        $keys        = include SITE_HOME.'/api_keys.php';
        $accessKeyId = $request->getHeader(self::HEADER_KEY_NAME)[0];
        if (!array_key_exists($accessKeyId, $keys)) { return false; }

        $credentials = new Credentials($accessKeyId, $keys[$accessKeyId]);
        $signer      = new SignatureV4('account_tracker', 'bloomington');
        $output      = $signer->signRequest($request, $credentials);

        return $output->getHeader(self::HEADER_AUTHORIZATION)[0] === $request->getHeader(self::HEADER_AUTHORIZATION)[0];
    }
}
