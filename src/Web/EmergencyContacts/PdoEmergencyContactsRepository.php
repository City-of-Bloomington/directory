<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\EmergencyContacts;

use Domain\EmergencyContacts\Contact;
use Domain\EmergencyContacts\Repository;
use Web\PdoRepository;

class PdoEmergencyContactsRepository extends PdoRepository implements Repository
{
    public const TABLE = 'emergencyContacts';

    public function find(): array
    {
        $sql   = 'select * from '.self::TABLE;
        $query = $this->pdo->query($sql);
        $contacts = [];
        foreach ($query->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $contacts[] = new Contact($row);
        }
        return $contacts;
    }

    public function load(string $username): ?Contact
    {
        $sql    = 'select * from '.self::TABLE. ' where username=?';
        $query  = $this->pdo->prepare($sql);
        $query->execute([$username]);
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        if (count($result)) {
            return new Contact($result[0]);
        }
        return null;
    }

    public function save(Contact $contact): int
    {
        return parent::saveToTable((array)$contact, self::TABLE);
    }

    public function delete(int $id)
    {
        $sql   = 'delete from '.self::TABLE.' where id=?';
        $query = $this->pdo->prepare($sql);
        $query->execute([$id]);
    }
}
