<?php

namespace App\Core;
use Exception;
class Controller {
    // public function view($view, $data = []) {
    //     extract($data);
    //     require '../App/views/'.$view.'.view.php';
    // }
    // public function render($view, $data = []) {        
    //     require '../App/views/layouts/template.view.php';
    // }
    // public function renderView($view, $data = []) {   
    //     extract($data);     
    //     require '../App/views/'.$view.'.view.php';
    // }

    public function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }
    public function getRequestData() {
        $method = $this->getMethod();
        $handlers = [
            'GET' => function () { return $_GET; },
            'POST' => function () { return $this->getJsonData() ?? $_POST; },
            'PUT' => function () { return $this->getJsonData(); },
            'DELETE' => function () { return $this->getJsonData(); },
        ];
        $handler = $handlers[$method] ?? null;
        $data = $handler ? $handler() : null;
        if (!is_array($data)) {
            throw new Exception('Invalid request data');
        }
        return $data;
    }

    private function getJsonData() {
        $data = file_get_contents('php://input');
        if (!$data) {
            return null;
        }
        $json = json_decode($data, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }
        return $json;
    }
    public function returnJson($data, $status = 200) {
        ob_clean(); // Clear the output buffer
        http_response_code($status);
        header("Content-Type: application/json");
        echo json_encode($data);
        exit;
    }
}