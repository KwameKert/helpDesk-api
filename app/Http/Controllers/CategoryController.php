<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

use App\Http\Resources\Student as StudentResource;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categories = DB::select( DB::raw(
            "SELECT id,name FROM `categories`  WHERE categories.`status`='Active'
            "));

          foreach($categories as $category
          ){
        $response[] = array('id' => $category->id,'text' => $category->name);
    }


        return collect($response);

       
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
        //
        $category = Category::whereName($request->name)->first();
        
        if($category){
            return response()->json(['error'=>'Oopps. Category name exists']);
        }
        $data = [
            'name' => $request->name,
            'status' => $request->status
        ];

        $category = Category::create($data);

        if($category)
        {
          return response()->json(['success'=> 'Category created successfully']);

        }else{

            return response()->json(['error' => 'Oops.. category not created']);
        }
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
        //
        $category = Category::findOrFail($id);
        
        $data = [
            'name' => $request->name,
            'status' => $request->status
        ]; 

        if($category->update($data)){
            return response()->json(['success'=>'Category updated succesffully']);
        }else{
            return response()->json(['error'=> 'Ooops... category not updated']);
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
        //
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['success'=>'Category deleted successfully']);
    }
}
