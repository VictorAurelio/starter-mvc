<?php

namespace App\Controllers;

use App\Core\Controller;
class ErrorHandlerController extends Controller {

    public function pageNotFound() {
        $this->render('error-pages/404');
    }

    public function invalidParameters() {
        echo 'INVALID PARAMETERS';
    }
}