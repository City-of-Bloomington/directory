<?php
/**
 * @copyright 2014-2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
use Application\Models\DepartmentGateway;
use Blossom\Classes\View;
?>
<section id="departments-numbers">
    <h1><?php echo $this->_('main_numbers'); ?></h1>
    <table>
        <thead>
            <tr>
                <th scope="col" style="text-align: right;">Name</th>
                <th scope="col">Phone</th>
                <th scope="col">Address</th>
            </tr>
        </thead>
        <tbody>
    <?php
        $departments = DepartmentGateway::getDepartments();

        function renderChildren($departments)
        {
            if (count($departments)) {
                foreach ($departments as $d) {
                    $name = View::escape($d->name);
                    $address = "{$d->address}\n{$d->city} {$d->state} {$d->zip}";
                    $phone = $d->office;
                    if ($phone) {
                        $breadcrumbs = $d->getBreadcrumbs();
                        if (count($breadcrumbs) > 2) {
                            array_pop($breadcrumbs);
                            end($breadcrumbs);
                            $parent = key($breadcrumbs);
                            if ($parent != 'City Hall') {
                                $name = "$parent - $name";
                            }
                        }

                        echo "
                        <tr><th>$name</th>
                            <td class=\"mod-number\">$phone</td>
                            <td><address>$address</address></td>
                        </tr>
                        ";
                        renderChildren($d->getChildren());
                    }
                }
            }
        }
        renderChildren($departments);
        /*
        $departments = DepartmentGateway::getTelephoneNumbers();

        $rows = [];
        foreach ($departments as $d) {
            $name = View::escape($d->name);
            $address = "{$d->address}\n{$d->city} {$d->state} {$d->zip}";
            $phone = $d->office;

            $breadcrumbs = $d->getBreadcrumbs();
            array_pop($breadcrumbs);
            end($breadcrumbs);
            $parent = key($breadcrumbs);
            $name = "$parent - $name";

            $rows[$name] = "
            <tr><th>$name</th>
                <td class=\"mod-number\">$phone</td>
                <td><address>$address</address></td>
            </tr>
            ";
        }
        ksort($rows);
        foreach ($rows as $html) { echo $html; }
        */
    ?>
        </tbody>
    </table>
</section>
