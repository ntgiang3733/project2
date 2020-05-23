<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Categories;
use App\Models\ProductTypes;
use App\Models\Products;
use Auth;
use Cart;

class HomeController extends Controller
{
    public function __construct() {
        $category = Categories::where('status',1)->get();
        $producttype = ProductTypes::where('status',1)->get();
        view()->share(['category' => $category , 'producttype' => '$producttype']);
    }

    public function index() {
        $product1 = Products::where('status',1)->where('idCategory',5)->get();
        $product2 = Products::where('status',1)->where('idCategory',6)->get();
        return view('client.pages.index',['proapple' => $product1, 'proandroid' => $product2]);
    }

    public function getDetail($slug) {
        $productDetail = Products::where('slug', $slug)->first();
        $idProType = ProductTypes::where('slug', $slug)->first();
        
        if ($productDetail) {
            return view('client.pages.detail', ['product' => $productDetail]);
        }
        else if( $idProType) {
            $productByProdType = Products::where('idProductType', $idProType->id)->get();
            return view('client.pages.detail_protype', ['product' => $productByProdType, 'producttype' => $idProType]);
        }
    }
}
