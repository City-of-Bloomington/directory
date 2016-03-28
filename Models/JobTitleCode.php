<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

use Blossom\Classes\ActiveRecord;

class JobTitleCode
{
    private $data = [];

    public  static $table  = 'COB.jobTitleCrosswalk';
    private static $fields = 'id, Code as code, Title as title';

    private static $invalidCharacters = '/[^a-zA-Z\-\s]/';

    public static function find()
    {
        $table  = self::$table;
        $fields = self::$fields;

        $sql = "select $fields from $table order by code";
        $rows = HRGateway::dbQuery($sql);

        $titles = [];
        foreach ($rows as $row) { $titles[] = new JobTitleCode($row); }
        return $titles;
    }

	/**
	 * Populates the object with data
	 *
	 * Passing in an associative array of data will populate this object without
	 * hitting the database.
	 *
	 * Passing in a scalar will load the data from the database.
	 * This will load all fields in the table as properties of this class.
	 * You may want to replace this with, or add your own extra, custom loading
	 *
	 * @param int|string|array $id (ID, Code)
	 */
	public function __construct($id=null)
	{
		if ($id) {
			if (is_array($id)) {
                $this->data = $id;
			}
			else {
                $pdo = HRGateway::getConnection();
                $table  = self::$table;
                $fields = self::$fields;
                $sql = "select $fields from $table";

                if ((is_int($id) && $id>0) || (is_string($id) && ctype_digit($id))) {
                    $id = (int)$id;
                    $sql.= " where id=$id";
                }
                else {
                    $id = preg_replace(self::$invalidCharacters, '', $id);
                    $sql.= " where Code='$id'";
                }

                $rows = HRGateway::dbQuery($sql);
                if (count($rows)) {
                    $this->data = $rows[0];
                }
                else {
                    throw new \Exception('JobTitleCodes/unknown');
                }
			}
		}
		else {
			// This is where the code goes to generate a new, empty instance.
			// Set any default values for properties that need it here
		}
	}

	public function validate()
	{
        if (!$this->getCode() || !$this->getTitle()) {
            throw new \Exception('missingRequiredFields');
        }
	}

	public function save()
	{
        $this->validate();

        $table = self::$table;

        if ($this->getId()) {
            $sql = "update $table
                    set Code='{$this->getCode()}', Title='{$this->getTitle()}'
                    where id={$this->getId()}";
        }
        else {
            $sql = "insert into $table (Code, Title)
                    values('{$this->getCode()}', '{$this->getTitle()}')";
        }
        HRGateway::dbQuery($sql);
	}

	//----------------------------------------------------------------
	// Generic Getters & Setters
	//----------------------------------------------------------------
    private function get($f)
	{
		if (isset( $this->data[$f])) {
			return $this->data[$f];
		}
	}

	public function getId   () { return $this->get('id'   ); }
	public function getCode () { return $this->get('code' ); }
	public function getTitle() { return $this->get('title'); }

	public function setCode ($s) { $this->data['code' ] = preg_replace(self::$invalidCharacters, '', $s); }
	public function setTitle($s) { $this->data['title'] = preg_replace(self::$invalidCharacters, '', $s); }

	public function handleUpdate(array $post)
	{
        $this->setCode ($post['code' ]);
        $this->setTitle($post['title']);
	}
}