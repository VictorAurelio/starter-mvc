<?php

namespace App\Core;
class Controller {
    public function view($view, $data = []) {
        extract($data);
        require '../App/views/'.$view.'.view.php';
    }
    public function render($view, $data = []) {        
        require '../App/views/layouts/template.view.php';
    }
    public function renderView($view, $data = []) {   
        extract($data);     
        require '../App/views/'.$view.'.view.php';
    }
}