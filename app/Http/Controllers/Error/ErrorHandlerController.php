<?php

namespace App\Http\Controllers\Error;

use App\Core\Base\BaseController;

class ErrorHandlerController extends BaseController
{
    public function pageNotFound()
    {
        http_response_code(404);
        $this->json(['message' => 'Page not found.'], 404);
    }

    public function invalidParameters()
    {
        http_response_code(400);
        $this->json(['message' => 'Invalid parameters.'], 400);
    }

    public function handleInvalidRequest() {
        http_response_code(400);
        $this->json(['message' => 'Invalid Request.'], 400);
    }
}
