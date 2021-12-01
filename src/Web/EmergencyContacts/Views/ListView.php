<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\EmergencyContacts\Views;

use Web\View;
use Domain\EmergencyContacts\Actions\Find\Response;

class ListView extends View
{
    public function __construct(Response $res)
    {
        parent::__construct();

        $this->vars = [
            'contacts' => $res->contacts,
            'fields'   => array_keys(self::$CONTACTFIELDS)
        ];
    }

    public function render(): string
    {
        if ($this->outputFormat == 'csv') {
            header('Content-type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="contacts.csv"');
            $csv = fopen('php://temp', 'r+');
            fputcsv($csv, self::$EVERBRIDGEFIELDS);
            foreach ($this->vars['contacts'] as $c) {
                $row = array_fill_keys(self::$EVERBRIDGEFIELDS, '');
                $row['External ID'] = 'cob.'.$c->username;
                $row['First Name' ] = $c->firstname;
                $row['Last Name'  ] = $c->lastname;
                $row['Country'    ] = 'US';
                $row['Record Type'] = 'City of Bloomington Employee';
                $row['Groups'     ] = '*|CoB';
                $row['Organization Name'] = 'City of Bloomington';
                foreach (self::$CONTACTFIELDS as $key=>$everBridgeName) {
                    $row[$everBridgeName] = $c->$key;
                }
                fputcsv($csv, $row);
            }
            rewind($csv);
            return stream_get_contents($csv);
        }

        return $this->twig->render($this->outputFormat.'/emergencyContacts/list.twig', $this->vars);
    }

    /**
     * Maps contact fields to the names used for Everbridge
     * The $key is the internal fieldname in the database
     * The #value is the name of the field for Everbridge.
     */
    public static $CONTACTFIELDS = [
        'email_1' => 'Email Address 1',
        'email_2' => 'Email Address 2',
        'email_3' => 'Email Address 3',
        'sms_1'   => 'SMS 1',
        'sms_2'   => 'SMS 2',
        'phone_1' => 'Phone 1',
        'phone_2' => 'Phone 2',
        'phone_3' => 'Phone 3',
        'tty_1'   => 'TTY 1'
    ];

    public static $EVERBRIDGEFIELDS = [
        'First Name',
        'Middle Initial',
        'Last Name',
        'Suffix',
        'External ID',
        'Organization Name',
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

}
