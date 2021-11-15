<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
use PHPUnit\Framework\TestCase;

use Web\View;

class ViewTest extends TestCase
{
    public function testRoutes()
    {
        $uri      = View::generateUri('departments.index');
        $expected = BASE_URI.'/departments';
        $this->assertEquals($expected, $uri);

        $path     = '/somewhere/else';
        $uri      = View::generateUri('departments.view', ['path'=>$path]);
        $expected = BASE_URI.'/departments'.$path;
        $this->assertEquals($expected, $uri);
    }
}
