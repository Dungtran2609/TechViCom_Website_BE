<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\News;

class HomeController extends Controller
{
    public function index()
    {
        return view('client.home');
    }
}
