<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductTypes;
use App\Models\Categories;

class AjaxController extends Controller
{
    public function getProductType(Request $request) {
        $producttype = ProductTypes::where('idCategory',$request->idCate)->get();
        return response()->json($producttype,200);

    }
}
