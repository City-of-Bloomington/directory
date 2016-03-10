<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\JobTitleCode;
use Blossom\Classes\Block;
use Blossom\Classes\Controller;

class JobTitleCodesController extends Controller
{
    public function index()
    {
        $titles = JobTitleCode::find();
        $this->template->blocks[] = new Block('jobTitleCodes/list.inc', ['jobTitleCodes'=>$titles]);
    }


    public function update()
    {
        if (!empty($_REQUEST['id'])) {
            try { $jobTitleCode = new JobTitleCode($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else {
            $jobTitleCode = new JobTitleCode();
        }

        if (isset($jobTitleCode)) {
            if (isset($_POST['code'])) {
                try {
                    $jobTitleCode->handleUpdate($_POST);
                    $jobTitleCode->save();
                    header('Location: '.BASE_URI.'/jobTitleCodes');
                    exit();
                }
                catch (\Exception $e) {
                    $_SESSION['errorMessages'][] = $e;
                }
            }
            $this->template->blocks[] = new Block('jobTitleCodes/updateForm.inc', ['jobTitleCode'=>$jobTitleCode]);
        }
        else {
            header('HTTP/1.1 404 Not Found', true, 404);
            $this->template->blocks[] = new Block('404.inc');
        }
    }
}