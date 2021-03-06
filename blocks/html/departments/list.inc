<?php
/**
 * @copyright 2014-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
use Application\Models\DepartmentGateway;
use Blossom\Classes\View;
?>
<section id="departments-list">
    <h1><?php echo $this->_(['department', 'departments', 2]); ?></h1>
    <?php
        $departments = DepartmentGateway::getDepartments();

        function renderChildren($departments, $nested = false)
        {
            if (count($departments)) {
                echo $nested === false
                    ? '<ul class="list-departments mod-index">'
                    : '<ul class="list-departments">';

                foreach ($departments as $d) {
                    $name = View::escape($d->getName());
                    echo "<li><a href=\"{$d->getUri()}\">$name</a>";

                    renderChildren($d->getChildren(), true);

                    echo "</li>";
                }
                echo '</ul>';
            }
        }
        renderChildren($departments);
    ?>
</section>
