<?php
/**
 * @copyright 2006-2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param User $this->user
 */
use Application\Models\User;
use Blossom\Classes\View;

$fields = array('firstname','lastname','email', 'username');
foreach ($fields as $field) {
    $get = 'get'.ucfirst($field);
    $$field = View::escape($this->user->$get());
}

$title = $this->user->getId() ? $this->_('user_edit') : $this->_('user_add');
?>
<section>
    <h1><?php echo $title; ?></h1>
	<form method="post" action="<?php echo BASE_URI; ?>/users/update">
        <fieldset><legend><?php echo $this->_('person_info'); ?></legend>
            <input name="user_id" type="hidden" value="<?php echo $this->user->getId(); ?>" />

            <dl><dt><label  for="firstname"><?php echo $this->_('firstname'); ?></label></dt>
                <dd><input name="firstname" id="firstname" value="<?php echo $firstname; ?>"
                        inputmode="latin-name" type="text" />
                </dd>
            </dl>

            <dl><dt><label  for="lastname"><?php echo $this->_('lastname'); ?></label></dt>
                <dd><input name="lastname" id="lastname" value="<?php echo $lastname; ?>"
                        inputmode="latin-name" type="text" />
                </dd>
            </dl>

            <dl><dt><label  for="email"><?php echo $this->_(['email', 'emails', 1]); ?></label></dt>
                <dd><input name="email" id="email" type="email" value="<?php echo $email; ?>" /></dd>
            </dl>
        </fieldset>
		<fieldset><legend><?php echo $this->_('user_info'); ?></legend>
            <dl><dt><label for="authenticationMethod"><?php echo $this->_('authenticationMethod'); ?></label></dt>
                <dd><select name="authenticationMethod" id="authenticationMethod" required="required">
                    <?php
                        foreach (User::getAuthenticationMethods() as $method) {
                            $selected = $this->user->getAuthenticationMethod()==$method
                                ? 'selected="selected"'
                                : '';
                            echo "<option $selected>$method</option>";
                        }
                    ?>
                    </select>
                </dd>
            </dl>
            <dl><dt><label  for="username"><?php echo $this->_('username'); ?></label></dt>
                <dd><input name="username" id="username" value="<?php echo $username; ?>" type="text" required="required" /></dd>
            </dl>
            <dl><dt><label  for="password"><?php echo $this->_('password'); ?></label></dt>
                <dd><input name="password" id="password" type="password" /></dd>
            </dl>
            <dl><dt><label for="role"><?php echo $this->_('role'); ?></label></dt>
                <dd><select name="role" id="role"><option></option>
                    <?php
                        global $ZEND_ACL;
                        foreach (array_reverse($ZEND_ACL->getRoles()) as $role) {
                            $selected = $this->user->getRole()==$role
                                ? 'selected="selected"'
                                : '';
                            echo "<option $selected>$role</option>";
                        }
                    ?>
                    </select>
                </dd>
            </dl>
			<?php
				$helper = $this->template->getHelper('saveAndCancelButtons');
				echo $helper->saveAndCancelButtons(BASE_URI.'/users');
			?>
		</fieldset>
	</form>
</section>
