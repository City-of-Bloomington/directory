<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
use Application\Models\DepartmentGateway;
use Blossom\Classes\View;
?>
<section id="departments-numbers">
    <h1><?php echo $this->_('main_numbers'); ?></h1>
    <table>
    <?php
        $gateway = new DepartmentGateway();
        $departments = $gateway->getTelephoneNumbers();

        foreach ($departments as $d) {
            $name = View::escape($d->getName());
            $address = "{$d->getAddress()}\n{$d->getCity()} {$d->getState()} {$d->getZip()}";
            $phones = $d->getPhones();
            if (!empty($phones['office'])) {
                $phone = View::escape($phones['office']);
            }
            echo "
            <tr><th>$name</th>
                <td>$phone</td>
                <td><address>$address</address></td>
            </tr>
            ";
        }
    ?>
    </table>
</section>