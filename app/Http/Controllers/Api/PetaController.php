<?php

namespace App\Http\Controllers\Api;

use App\Models\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PetaController extends Controller
{
    public function getData(Request $request)
    {
        $carbon = Carbon::with(['desa', 'kategori'])->where('kategori_id', $request->kategori_id)->get();
        $columns = ['id', 'kategori_id', 'desa_id'];

        if ($request->has('subCategory')) {
            $columns[] = $request->subCategory;
        }

        return response()->json([
            "status" => 200,
            "message" => "success",
            "data" => $carbon
        ]);
    }
}
