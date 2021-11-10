<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web;

use Aura\Di\Container;

class Controller
{
    protected const ITEMS_PER_PAGE = 20;
    protected $di;

    public function __construct(Container $container)
    {
        $this->di = $container;
    }
}
