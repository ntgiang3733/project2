<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Categories;
use App\Models\ProductTypes;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use Mail;
use App\Mail\ShoppingMail;
use Auth;
use Cart;

class CartController extends Controller
{
    public function __construct() {
        $category = Categories::where('status',1)->get();
        $producttype = ProductTypes::where('status',1)->get();
        view()->share(['category' => $category , 'producttype' => '$producttype']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cart = Cart::content();
        return view('client.pages.cart',compact('cart'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['money'] = str_replace(',', '', $request->money);
        $data['idUser'] = Auth::user()->id;
        $data['code_order'] = 'order'.rand();
        $data['status'] = 0;
        $order = Order::create($data);
        $idOrder = $order->id;
        $orderdetail = [];
        $orderdetails = []; 
        foreach( Cart::content() as $key => $cart ){
            $orderdetail['idOrder'] = $idOrder;
            $orderdetail['idProduct'] = $cart->id;
            $orderdetail['quantity'] = $cart->qty;
            $orderdetail['price'] = $cart->price;
            $orderdetails[$key] = OrderDetail::create($orderdetail);
        }
        //Mail::to($order->email)->send(new ShoppingMail($order,$orderdetails));
        Cart::destroy();
        return response()->json('Đã mua hàng thành công',200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->ajax()) {
            Cart::update($id,$request->qty);
            return response()->json(['result' => 'Da sua thanh cong']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cart::remove($id);
            return response()->json(['result' => 'Da xoa thanh cong']);
    }

    public function addCart($id, Request $request) {
        $product = Products::find($id);
        if($request->qty) {
            $qty = $request->qty;
        } else {
            $qty = 1;
        }
        if($product->promotional > 0) {
            $price = $product->promotional;
        } else {
            $price = $product->price;
        }
        $cart = ['id' => $id , 'name' => $product->name , 'qty' => $qty , 'price' => $price , 'options' => ['img' => $product->image]];
        Cart::add($cart);
        return back()->with('thongbao','Da them san pham vao gio hang');
    }

    public function checkout() {
        $user = Auth::user();
        $price = str_replace(',' , '' , Cart::total());
        return view('client.pages.checkout', compact('user','price'));
    }
}
