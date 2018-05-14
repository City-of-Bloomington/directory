<?php
/**
 * @copyright 2014-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Department $this->department
 */
use Application\Models\DepartmentGateway;
use Application\Models\DirectoryAttributes as Attr;
use Blossom\Classes\Block;
use Blossom\Classes\View;
?>
<nav class="breadcrumbs">
<?php
    $i = count($this->department->getBreadcrumbs());
    foreach   ($this->department->getBreadcrumbs() as $name=>$dn) {
        $url = $name == 'Departments'
            ? BASE_URI
            : BASE_URI.DepartmentGateway::getPathForDn($dn);
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

        $block  = new Block('people/list.inc');
        $search = !empty($_GET[Attr::PROMOTED]) ? [Attr::PROMOTED => $_GET[Attr::PROMOTED]] : [];
        $staff  = DepartmentGateway::search($this->department->entry['dn'], $search);
        $people = $this->department->filterTopLevelPeople($staff);
        if (count($people)) {
            $block->people = $people;
            echo $block->render('html', $this->template);
        }

        $c = $this->department->getChildren();
        renderChildren($c, $block, $staff);

        function renderChildren($departments, $block, &$staff)
        {
            if (count($departments)) {
                echo '<ul class="list-departments">';
                foreach ($departments as $d) {
                    $name = View::escape($d->name);
                    echo "<li><a href=\"{$d->getUri()}\">$name</a>";

                    $people = $d->filterTopLevelPeople($staff);
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
