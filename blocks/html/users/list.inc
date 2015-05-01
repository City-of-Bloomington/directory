<?php
/**
 * @copyright 2007-2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Zend\Db\Sql\Select $users
 */
use Application\Models\User;
use Application\Templates\Helpers\ButtonLink;
use Blossom\Classes\View;
?>
<section>
	<h1 class="page-title"><?php echo $this->_(['user','users',2]); ?></h1>
	<div>
	<?php
        $helper = $this->template->getHelper('buttonLink');
        if (User::isAllowed('users')) {
            echo $helper->buttonLink(BASE_URI.'/users/update', $this->_('user_add'), 'add');
        }
	?>
	</div>
	<table>
		<thead>
			<tr><th scope="col"></th>
				<th scope="col"><?php echo $this->_('username'); ?></th>
				<th scope="col"><?php echo $this->_('name'); ?></th>
				<th scope="col"><?php echo $this->_('authenticationMethod'); ?></th>
				<th scope="col"><?php echo $this->_('role'); ?></th>
				<th scope="col"></th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->users as $user) {
				$editButton = '';
				$deleteButton = '';
				if (User::isAllowed('users')) {
					$editButton = $helper->buttonLink(
						BASE_URI.'/users/update?user_id='.$user->getId(),
						$this->_('user_edit'),
						'edit'
						#ButtonLink::SIZE_ICON
					);
					$deleteButton = $helper->buttonLink(
						BASE_URI.'/users/delete?user_id='.$user->getId(),
						$this->_('user_delete'),
						'delete'
						#ButtonLink::SIZE_ICON
					);
				}

				$name = View::escape($user->getFullname());
				echo "
				<tr><td>$editButton</td>
					<td>{$user->getUsername()}</td>
					<td>$name</td>
					<td>{$user->getAuthenticationMethod()}</td>
					<td>{$user->getRole()}</td>
					<td>$deleteButton</td>
				</tr>
				";
			}
		?>
		</tbody>
	</table>
</section>
