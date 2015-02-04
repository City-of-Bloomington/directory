<?php
/**
 * @copyright 2014-2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
use Application\Models\DepartmentGateway;
use Blossom\Classes\View;

global $DIRECTORY_CONFIG;
?>
<section id="departments-list">
    <h1><?php echo $this->_(['department', 'departments', 2]); ?></h1>
    <?php
        $gateway = new DepartmentGateway();
        $departments = $gateway->getDepartments();

        function renderChildren($departments, $nested = false)
        {
            if (count($departments)) {
                echo $nested === false
                    ? '<ul class="list-departments mod-index">'
                    : '<ul class="list-departments">';

                foreach ($departments as $d) {
                    $name = View::escape($d->name);
                    $url = BASE_URI.'/departments/view?dn='.urlencode($d->dn);
                    echo "<li><a href=\"$url\">$name</a>";

                    renderChildren($d->getChildren(), true);

                    echo "</li>";
                }
                echo '</ul>';
            }
        }
        renderChildren($departments);
    ?>
</section>
