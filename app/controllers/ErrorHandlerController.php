<?php

namespace App\Controllers;

use App\Core\Base\BaseController;

class ErrorHandlerController extends BaseController
{

    public function pageNotFound()
    {
        // $this->render('error-pages/404');
    }

    public function invalidParameters()
    {
        echo 'INVALID PARAMETERS';
    }
}
