<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Users\Actions\Update;

use Web\Authentication\AuthenticationService;
use Domain\Users\Entities\User;
use Domain\Users\DataStorage\UsersRepository;

class Command
{
    private $repo;
    private $auth;

    public function __construct(UsersRepository $repository, AuthenticationService $auth)
    {
        $this->repo = $repository;
        $this->auth = $auth;
    }

    public function __invoke(Request $req): Response
    {
        if ($req->authentication_method != 'local'
            && (empty($req->firstname) || empty($req->lastname) || empty($req->email))) {

            $o = $this->auth->externalIdentify($req->authentication_method, $req->username);
            if ($o) {
                if (empty($req->firstname)) { $req->firstname = $o->firstname; }
                if (empty($req->lastname )) { $req->lastname  = $o->lastname;  }
                if (empty($req->email    )) { $req->email     = $o->email;     }
            }
        }

        $errors = $this->validate($req);
        if ($errors) { return new Response(null, $errors); }

        $user = new User((array)$req);
        if ( $req->password) {
            $user->password = $this->auth->password_hash($req->password);
        }

        try {
            $id  = $this->repo->save($user);
            $res = new Response($id);
        }
        catch (\Exception $e) {
            $res = new Response(null, [$e->getMessage()]);
        }
        return $res;
    }

    private function validate(Request $req): array
    {
        $errors = [];
        if (!$req->firstname) { $errors[] = 'missingFirstname'; }
        if (!$req->lastname ) { $errors[] = 'missingLastname';  }
        if (!$req->email    ) { $errors[] = 'missingEmail';     }
        if (!$req->username ) { $errors[] = 'missingUsername';  }
        if (!$req->role     ) { $errors[] = 'missingRole';      }
        if (!$req->authentication_method ) { $errors[] = 'missingAuthenticationMethod'; }
        return $errors;
    }
}
