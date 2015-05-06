<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Zend\Db\Results $this->contacts
 */
use Application\Models\EmergencyContact;

header('Content-Disposition: attachment; filename="contacts.csv"');

$everbridgeFields = [
    'First Name',
    'Middle Initial',
    'Last Name',
    'Suffix',
    'External ID',
    'Country',
    'Business Name',
    'Record Type',
    'Groups',
    'Group Remove',
    'Location 1',
    'Street Address 1',
    'Apt/Suite/Unit 1',
    'City 1',
    'State/Province 1',
    'Postal Code 1',
    'Country 1',
    'Latitude 1',
    'Longitude 1',
    'Location 2',
    'Street Address 2',
    'Apt/Suite/Unit 2',
    'City 2',
    'State/Province 2',
    'Postal Code 2',
    'Country 2',
    'Latitude 2',
    'Longitude 2',
    'Location 3',
    'Street Address 3',
    'Apt/Suite/Unit 3',
    'City 3',
    'State/Province 3',
    'Postal Code 3',
    'Country 3',
    'Latitude 3',
    'Longitude 3',
    'Location 4',
    'Street Address 4',
    'Apt/Suite/Unit 4',
    'City 4',
    'State/Province 4',
    'Postal Code 4',
    'Country 4',
    'Latitude 4',
    'Longitude 4',
    'Location 5',
    'Street Address 5',
    'Apt/Suite/Unit 5',
    'City 5',
    'State/Province 5',
    'Postal Code 5',
    'Country 5',
    'Latitude 5',
    'Longitude 5',
    'Extension Phone 1',
    'Extension 1',
    'Extension Phone Country 1',
    'Extension Phone 2',
    'Extension 2',
    'Extension Phone Country 2',
    'Extension Phone 3',
    'Extension 3',
    'Extension Phone Country 3',
    'Extension Phone 4',
    'Extension 4',
    'Extension Phone Country 4',
    'Extension Phone 5',
    'Extension 5',
    'Extension Phone Country 5',
    'Phone 1',
    'Phone Country 1',
    'Phone 2',
    'Phone Country 2',
    'Phone 3',
    'Phone Country 3',
    'Phone 4',
    'Phone Country 4',
    'Phone 5',
    'Phone Country 5',
    'Phone 6',
    'Phone Country 6',
    'Email Address 1',
    'Email Address 2',
    'Email Address 3',
    'Plain Text Email - 1 way',
    'Plain Text - 1 way Pager Service',
    'Plain Text Email - 2 way',
    'SMS 1',
    'SMS 1 Country',
    'SMS 2',
    'SMS 2 Country',
    'FAX 1',
    'FAX Country 1',
    'FAX 2',
    'FAX Country 2',
    'FAX 3',
    'FAX Country 3',
    'TTY 1',
    'TTY Country 1',
    'TTY 2',
    'TTY Country 2',
    'TTY 3',
    'TTY Country 3',
    'Numeric Pager',
    'Numeric Pager Country',
    'Numeric Pager Pin',
    'Numeric Pager Service',
    'TAP Pager',
    'TAP Pager Country',
    'TAP Pager Pin',
    'One Way SMS',
    'One Way SMS Country',
    'Custom Field 1',
    'Custom Value 1',
    'Custom Field 2',
    'Custom Value 2',
    'Custom Field 3',
    'Custom Value 3',
    'Custom Field 4',
    'Custom Value 4',
    'Custom Field 5',
    'Custom Value 5',
    'Custom Field 6',
    'Custom Value 6',
    'Custom Field 7',
    'Custom Value 7',
    'Custom Field 8',
    'Custom Value 8',
    'Custom Field 9',
    'Custom Value 9',
    'END'
];
echo implode(',', $everbridgeFields)."\n";

foreach ($this->contacts as $contact) {
    $person = $contact->getPerson();
    if ($person) {
        $row = array_fill_keys($everbridgeFields, '');
        $row['External ID'] = 'cob'.$contact->getEmployeeNum();
        $row['First Name' ] = str_replace(',', '', $person->firstname);
        $row['Last Name'  ] = str_replace(',', '', $person->lastname);
        $row['Country'    ] = 'US';
        $row['Record Type'] = 'City of Bloomington Employee';
        $row['Groups'     ] = '*|CoB '.$contact->getDepartment();

        foreach (EmergencyContact::$contactFields as $key=>$everBridgeName) {
            $get = 'get'.ucfirst($key);
            $row[$everBridgeName] = str_replace(',', ' ', $contact->$get());
        }
        $line = implode(',', $row);
        echo "$line\n";
    }
}
