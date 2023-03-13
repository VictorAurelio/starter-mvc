<?php

namespace App\Core\Base;

use App\Core\Base\Exceptions\BaseInvalidRequestException;

class Controller
{
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
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    public function getRequestData()
    {
        $method = $this->getMethod();
        $data = match ($method) {
            'GET' => $_GET,
            'POST' => $this->getJsonData() ?? $_POST,
            'PUT', 'DELETE' => $this->getJsonData(),
            default => null,
        };
        if (!is_array($data)) {
            throw new BaseInvalidRequestException('Invalid request data');
        }
        // header('Content-Type: application/json');
        return $data;
    }

    private function getJsonData()
    {
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
    public function json($data, $status = 200)
    {
        ob_clean(); // Clear the output buffer
        http_response_code($status);
        header("Content-Type: application/json");
        echo json_encode($data);
        exit;
    }
    private function getBaseUrl()
    {
        $base = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://';
        $base .= $_SERVER['SERVER_NAME'];
        if ($_SERVER['SERVER_PORT'] != '80') {
            $base .= ':' . $_SERVER['SERVER_PORT'];
        }
        $base .= '/mvcscratch/public';

        return $base;
    }
    protected function redirect($url)
    {
        header("Location: " . $this->getBaseUrl() . $url);
        exit;
    }
}
