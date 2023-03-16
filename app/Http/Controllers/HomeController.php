<?php

namespace App\Http\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        echo ' home ';
    }
}
