<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\JobTitles\Actions\Update;

class Request
{
    public $id;
    public $code;
    public $title;

    public function __construct(?array $data=null)
    {
        if (!empty($data['id'   ])) { $this->id    = (int)$data['id'   ]; }
        if (!empty($data['code' ])) { $this->code  =      $data['code' ]; }
        if (!empty($data['title'])) { $this->title =      $data['title']; }
    }
}
