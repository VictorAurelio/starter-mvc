<?php
function setCorsHeaders() {
    header('Access-Control-Allow-Origin: *');
    // header('Access-Control-Allow-Origin: http://127.0.0.1:5501');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    // header('Access-Control-Max-Age: 86400');
}