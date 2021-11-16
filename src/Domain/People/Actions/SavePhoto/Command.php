<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\People\Actions\SavePhoto;

use Domain\Departments\DataStorage\DepartmentsGateway;

class Command
{
    public static $validPhotoFormats = ['image/jpeg'];
    private $gw;
    
    public function __construct(DepartmentsGateway $gateway)
    {
        $this->gw = $gateway;
    }

    public function __invoke(Request $req): Response
    {
        try {
            $person = $this->gw->getPerson($req->username);
        }
        catch (\Exception $e) {
            return new Response(null, [$e->getMessage()]);
        }
        
        $errors = $this->validate($req);
        if ($errors) {
            return new Response($person, $errors);
        }
        
        try {
            $this->gw->savePhoto($person, $req->file);
            return new Response($person);
        }
        catch (\Exception $e) {
            return new Response($person, [$e->getMessage()]);
        }
    }

    private function validate(Request $req): array
    {
        $errors = [];
        if (!in_array(mime_content_type($req->file), self::$validPhotoFormats)) {
            $errors[] = 'media/unknownFileType';
        }
        return $errors;
    }
}
