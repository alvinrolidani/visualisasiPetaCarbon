<?php

namespace App\Http\Controllers;

use App\Models\Carbon;
use App\Models\Geojson;
use Illuminate\Http\Request;

class Home extends Controller
{
    public function index()
    {
        $carbon = Carbon::with('desa')->get();
        $geojson = Geojson::all();
        return view('peta', compact('geojson', 'carbon'));
    }
}
