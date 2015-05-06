<?php
/**
 * @copyright 2014-2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Department $this->department
 */
use Application\Models\DepartmentGateway;
use Blossom\Classes\Block;
use Blossom\Classes\View;
?>
<nav class="breadcrumbs">
<?php
    $i = count($this->department->getBreadcrumbs());
    foreach   ($this->department->getBreadcrumbs() as $name=>$dn) {
        $url = $name == 'Departments'
            ? BASE_URI
            : BASE_URI.'/departments'.DepartmentGateway::getPathForDn($dn);
        $name = View::escape($name);
        $i--;
        if( $i > 0) {
            echo "<span><a href=\"$url\">$name</a></span>";
        } else {
            echo "<span>$name</span>";
        }
    }
?>
</nav>

<section>
    <h1><?php echo View::escape($this->department->name); ?></h1>
    <?php
        $block = new Block('directoryAttributes/contactInfo.inc', ['entry'=>$this->department]);
        echo $block->render('html', $this->template);

        $block = new Block('people/list.inc');

        # Grab all the people inside this department, including sub-departments
        $staff = $this->department->getPeople();
        $c = count($staff);

        # Are there any people in just this top-level department?
        $people = $this->department->getPeople($staff);
        if (count($people)) {
            $block->people = $people;
            echo $block->render('html', $this->template);
        }

        $c = $this->department->getChildren();
        renderChildren($c, $block, $staff);

        function renderChildren($departments, $block, &$staff)
        {
            $c = count($staff);

            if (count($departments)) {
                echo '<ul class="list-departments">';
                foreach ($departments as $d) {
                    $name = View::escape($d->name);
                    echo "<li><a href=\"{$d->getUri()}\">$name</a>";

                    $people = $d->getPeople($staff);
                    if (count($people)) {
                        $block->people = $people;
                        echo $block->render('html', $block->template);
                    }

                    renderChildren($d->getChildren(), $block, $staff);

                    echo "</li>";
                }
                echo '</ul>';
            }
        }
    ?>
</section>
