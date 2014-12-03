<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Department $this->department
 */
use Blossom\Classes\Block;
use Blossom\Classes\View;
?>
<nav>
<?php
    foreach ($this->department->getPath() as $name=>$dn) {
        $url = $name == 'Departments'
            ? BASE_URI
            : BASE_URI.'/departments/view?dn='.urlencode($dn);
        $name = View::escape($name);
        echo "<a href=\"$url\">$name></a>";
    }
?>
</nav>
<section>
    <h1><?php echo View::escape($this->department->getName()); ?></h1>
    <?php
        $block = new Block('people/list.inc', ['people'=>$this->department->getPeople()]);
        echo $block->render('html', $this->template);

        $c = $this->department->getChildren();
        renderChildren($c, $block);

        function renderChildren($departments, $block)
        {
            if (count($departments)) {
                echo '<ul>';
                foreach ($departments as $d) {
                    $name = View::escape($d->getName());
                    $url = BASE_URI.'/departments/view?dn='.urlencode($d->getDn());
                    echo "<li><a href=\"$url\">$name</a>";

                    $people = $d->getPeople();
                    if (count($people)) {
                        $block->people = $people;
                        echo $block->render('html', $block->template);
                    }

                    renderChildren($d->getChildren(), $block);

                    echo "</li>";
                }
                echo '</ul>';
            }
        }
    ?>
</section>