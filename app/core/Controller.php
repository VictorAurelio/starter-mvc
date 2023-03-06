<?php

namespace App\Core;
class Controller {
    public function loadView($view, $data = []) {
        require '../App/views/'.$view.'.php';
    }
}