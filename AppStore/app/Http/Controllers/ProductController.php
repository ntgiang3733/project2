<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\ProductTypes;
use File;
use App\Http\Requests\StoreProductRequest;
use Validator;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Products::where('status',1)->paginate(5);
        return view('admin.pages.product.list',compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = Categories::where('status',1)->get();
        $producttype = ProductTypes::where('status',1)->get();
        return view('admin.pages.product.add',compact('category','producttype'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        if($request->hasFile('image')) {
            $file = $request->image;
            $file_name = $file->getClientOriginalName();
            $file_type = $file->getMimeType();
            $file_size = $file->getSize();
            if($file_type == 'image/png' || $file_type == 'image/jpg' || $file_type == 'image/jpeg' || $file_type == 'image/gif') {
                if($file_size <= 1048576) {
                    $file_name = date('D-m-yyyy').'_'.rand().'_'.utf8tourl($file_name);
                    if($file->move('img/upload/product',$file_name)) {
                        $data = $request->all();
                        $data['slug'] = utf8tourl($request->name);
                        $data['image'] = $file_name;
                        Products::create($data);
                        return redirect()->route('product.index')->with('thongbao','Da them thanh cong san pham moi');
                    }
                }else {
                    return back()->with('error' , 'Ban khong the upload anh qua 1Mb');
                }
            } else {
                return back()->with('error' , 'File anh khong dung dinh dang');
            }
        } else {
            return back()->with('error' , 'Ban chua them anh minh hoa cho san pham');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Categories::where('status',1)->get();
        $producttype = ProductTypes::where('status',1)->get();
        $product = Products::find($id);
        return response()->json(['category' => $category, 'producttype' => $producttype, 'product' => $product], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:2|max:255',
            'description' => 'required|min:2',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'promotional' => 'numeric',
            'image' => 'image',
        ],[
            'required' => ':attribute không được bỏ trống',
            'min' => ':attribute tối thiểu có 2 ký tự',
            'max' => ':attribute tối đa có 255 ký tự',
            'numeric' => ':attribute phải là một số ',
            'image' => ':attribute không là hình ảnh',
            'unique' => ':attribute đã tồn tại trong hệ thống',
        ],[
            'name' => 'Tên sản phẩm',
            'description' => 'Mô tả sản phẩm',
            'quantity' => 'Số lượng sản phẩm',
            'price' => 'Đơn giá sản phẩm',
            'promotional' => 'Giá khuyến mại',
            'image' => 'Ảnh minh họa',
        ]);

        if($validator->fails()) {
            return response()->json(['error' => 'true' , 'message' => $validator->errors()],200);
        }
        $product = Products::find($id);
        $data = $request->all();
        $data['slug'] = utf8tourl($request->name);
        if($request->hasFile('image')) {
            $file = $request->image;
            $file_name = $file->getClientOriginalName();
            $file_type = $file->getMimeType();
            $file_size = $file->getSize();
            if($file_type == 'image/png' || $file_type == 'image/jpg' || $file_type == 'image/jpeg' || $file_type == 'image/gif') {
                if($file_size <= 1048576) {
                    $file_name = date('D-m-yyyy').'_'.rand().'_'.utf8tourl($file_name);
                    if($file->move('img/upload/product',$file_name)) {
                        
                        $data['image'] = $file_name;
                        if(File::exists('img/upload/product/'.$product->image)) {
                            unlink('img/upload/product/'.$product->image);
                        }
                    }
                }else {
                    return response()->json(['error' , 'Anh dung luong vuot cho phep tren 1Mb'],200);
                }
            } else {
                return response()->json('error' , 'File anh khong dung dinh dang');
            }
        } else {
            $data['image'] = $product->image;
        }
        $product->update($data);
        return response()->json(['result' => 'Da sua thanh cong san pham co id la : ' . $id],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Products::find($id);
        if(File::exists('img/upload/product/'.$product->image)) {
            unlink('img/upload/product/'.$product->image);
        }
        $product->delete();
        return response()->json(['result' => 'Da xoa thanh cong san pham co id la : ' .$id],200);
    }
}
