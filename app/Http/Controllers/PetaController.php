<?php

namespace App\Http\Controllers;

use App\Models\Carbon;
use App\Models\Geojson;
use Illuminate\Http\Request;

class PetaController extends Controller
{
    public function index()
    {
        $carbon = Carbon::with('desa')->get();
        $geojson = Geojson::all();
        $breadcumb = 'Visualisasi Peta';
        $icon = 'mdi mdi-map';
        return view('peta', compact('geojson', 'carbon', 'breadcumb', 'icon'));
    }
}
