<?php

namespace App\Http\Controllers;

use App\Models\Carbon;
use App\Models\Geojson;
use Illuminate\Http\Request;

class Home extends Controller
{
    public function index()
    {

        $breadcumb = 'Dashboard';
        $icon = 'mdi mdi-home';
        return view('home', compact('breadcumb', 'icon'));
    }
}
