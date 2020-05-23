<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Http\Requests\StoreCategoryRequest;
use Validator;
use Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $category = Categories::paginate(5);
        if($user->can('view',Categories::class)) {
            
            return view('admin.pages.category.list',\compact("category"));
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $category = Categories::paginate(5);
        if($user->can('create',Categories::class)) {
            
            return view('admin.pages.category.add');
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $user = Auth::user();
        $category = Categories::paginate(5);
        if($user->can('create',Categories::class)) {
            Categories::create([
                'name' => $request->name,
                'slug' => utf8tourl($request->name),
                'status' => $request->status
            ]);
            return redirect()->route('category.index');
            
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        $category = Categories::paginate(5);
        if($user->can('update',Categories::class)) {
            $category = Categories::find($id);
            return response()->json($category,200);
            
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $category = Categories::paginate(5);
        if($user->can('update',Categories::class)) {
            $validator = Validator::make($request->all(),[
                'name' => 'required|min:2|max:255'

            ],[
                'required' => 'Ten danh muc san pham khong duoc de trong',
                'min' => 'Ten danh muc san pham phai du tu 2-255 ky tu',
                'max' => 'Ten danh muc san pham phai du tu 2-255 ky tu',  
            ]);

            if($validator->fails()) {
                return response()->json([ 'error'=>'true','message' => $validator -> errors() ],200);
            }

            $category = Categories::find($id);
            $category->update([
                'name' => $request->name,
                'slug' => utf8tourl($request->name),
                'status' => $request->status
            ]);

            return response()->json(['success' => 'them thanh cong'] );
            
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $category = Categories::paginate(5);
        if($user->can('delete',Categories::class)) {
            $category = Categories::find($id);
            $category->delete();
            return response()->json(['success' => 'Xoa thanh cong']);
            
        }
        
    }
}
