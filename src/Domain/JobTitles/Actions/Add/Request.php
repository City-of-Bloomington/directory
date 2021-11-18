<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\JobTitles\Actions\Add;

class Request
{
    public $code;
    public $title;

    public function __construct(?array $data=null)
    {
        if (!empty($data['code' ])) { $this->code  = $data['code' ]; }
        if (!empty($data['title'])) { $this->title = $data['title']; }
    }
}
