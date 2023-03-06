<?php

namespace App\Controllers;
class ErrorHandlerController {

    public function pageNotFound() {
        echo '404 PAGE NOT FOUND!!';
    }

    public function invalidParameters() {
        echo 'INVALID PARAMETERS';
    }
}