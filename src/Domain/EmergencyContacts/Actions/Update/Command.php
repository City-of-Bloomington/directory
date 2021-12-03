<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\EmergencyContacts\Actions\Update;

use Domain\EmergencyContacts\Contact;
use Domain\EmergencyContacts\Repository;

class Command
{
    private $repo;

    public function __construct(Repository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(Contact $contact): Response
    {
        $errors = self::validate($contact);
        if ($errors) {
            return new Response($contact->id, $errors);
        }

        try {
            $id = $this->repo->save($contact);
            return new Response($id);
        }
        catch (\Exception $e) {
            return new Response($contact->id, [$e->getMessage()]);
        }
    }

    private static function validate(Contact $c): array
    {
        $errors = [];
        if (!$c->username ) { $errors[] = 'missingUsername';  }
        if (!$c->firstname) { $errors[] = 'missingFirstname'; }
        if (!$c->lastname ) { $errors[] = 'missingLastname';  }

        if ($c->email_1 && !self::isValidEmail($c->email_1)) { $errors[] = 'invalidEmail'; }
        if ($c->email_2 && !self::isValidEmail($c->email_2)) { $errors[] = 'invalidEmail'; }
        if ($c->email_3 && !self::isValidEmail($c->email_3)) { $errors[] = 'invalidEmail'; }
        if ($c->sms_1   && !self::isValidPhone($c->sms_1  )) { $errors[] = 'invalidPhone'; }
        if ($c->sms_2   && !self::isValidPhone($c->sms_2  )) { $errors[] = 'invalidPhone'; }
        if ($c->phone_1 && !self::isValidPhone($c->phone_1)) { $errors[] = 'invalidPhone'; }
        if ($c->phone_2 && !self::isValidPhone($c->phone_2)) { $errors[] = 'invalidPhone'; }
        if ($c->phone_3 && !self::isValidPhone($c->phone_3)) { $errors[] = 'invalidPhone'; }
        if ($c->tty_1   && !self::isValidPhone($c->tty_1  )) { $errors[] = 'invalidPhone'; }
        return $errors;
    }

    private static function isValidEmail(string $string): bool
    {
        $regex = "|^[a-zA-Z0-9.!#$%&'*+/=?^_`{\|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$|";
        return preg_match($regex, $string) ? true : false;
    }

    private static function isValidPhone(string $string): bool
    {
        return preg_match('|^\d{10}$|', $string) ? true : false;
    }
}
