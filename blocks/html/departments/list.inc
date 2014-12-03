<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
use Application\Models\DepartmentGateway;
use Blossom\Classes\View;

global $DIRECTORY_CONFIG;
?>
<section id="departments-list">
    <h1><?php echo $this->_(['department', 'departments', 2]); ?></h1>
    <div>
    <?php
        $gateway = new DepartmentGateway();
        $departments = $gateway->getDepartments();

        renderChildren($departments);

        function renderChildren($departments)
        {
            if (count($departments)) {
                echo '<ul>';
                foreach ($departments as $d) {
                    $name = View::escape($d->getName());
                    $url = BASE_URI.'/departments/view?dn='.urlencode($d->getDn());
                    echo "<li><a href=\"$url\">$name</a>";

                    renderChildren($d->getChildren());

                    echo "</li>";
                }
                echo '</ul>';
            }
        }
    ?>
    </div>
</section>
