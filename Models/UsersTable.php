<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Models;

use Blossom\Classes\TableGateway;
use Zend\Db\Sql\Select;

class UsersTable extends TableGateway
{
	public function __construct() { parent::__construct('users', __namespace__.'\User'); }

	/**
	 * @param array $fields
	 * @param string|array $order Multi-column sort should be given as an array
	 * @param bool $paginated Whether to return a paginator or a raw resultSet
	 * @param int $limit
	 */
	public function find($fields=null, $order='lastname', $paginated=false, $limit=null)
	{
		$select = new Select('users');
		if (count($fields)) {
			foreach ($fields as $key=>$value) {
                $select->where([$key=>$value]);
			}
		}
		return parent::performSelect($select, $order, $paginated, $limit);
	}
}
