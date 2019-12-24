<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Category;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.pages.categories.list");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::with("children")->whereNull("category_id")->get();

        return view("admin.pages.categories.create")->with(["categories"=>$categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->except('_token'), [
            'name' => 'required|min:3|max:250',
            'category_id' => 'required|numeric'
        ]);

        if ($validation->fails()) {
            return back()->withErrors($validation);
        }
        if($request->input("category_id") != 0){
            $parent_category = Category::where("id",$request->input("category_id"))->get()->first();
            if($parent_category == null){
                return back()->with("error", "Parent category not found with given id.");
            }
        }

        $category = new Category();
        $category->name = $request->input("name");
        if(isset($parent_category)){
            $category->parent()->associate($parent_category->id);
        }
        $category->save();
        Cache::tags("categories_all")->flush();

        return redirect(route("categoryMain"))->with("success", "Category successfully inserted."); 

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
        $categories = Category::with("children")->whereNull("category_id")->get();

        $category = Category::where("id",$id)->get()->first();
        if(is_null($category)){
            return back()->with("error","Girdi bulunamadı!");
        }
        return view("admin.pages.categories.edit")->with([
            "category" => $category,
            "categories" => $categories
        ]);
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
        $validation = Validator::make($request->except('_token'), [
            'name' => 'required|min:3|max:250',
            'category_id' => 'required|numeric'
        ]);

        if ($validation->fails()) {
            return back()->withErrors($validation);
        }

        if ($request->input("category_id") != 0) {
            $parent_category = Category::where("id", $request->input("category_id"))->get()->first();
            if ($parent_category == null) {
                return back()->with("error", "Couln't find parent category with the given id.");
            }
        }

        if($id == $request->input("category_id")){
            return back()->with("error","A category can't selected as parent of itself!");
        }

        $category = Category::find($id);
        $category->name = $request->input("name");
        if (isset($parent_category)) {
            $category->category_id = $parent_category->id;
        }else{
            $category->category_id = null;
        }
        $category->save();

        Cache::tags("categories_all")->flush();

        return redirect(route("categoryMain"))->with("success", "Category successfully updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $category = Category::find($id);
            $category->delete();
            
        }catch(\Exception $ex){
            return redirect(route("categoryMain"))->with("error", "Some errors occured!"); 
        }
        Cache::tags("categories_all")->flush();
        return redirect(route("categoryMain"))->with("success", "Category successfully deleted."); 

    }

    /**
     * Function for datatable list ajax requests
     *
     * @return string string contains datatables json data
     */
    public function ajax(){
        
        $categories = Category::with("parent")->get()->toArray();
        // parent array dönmekte bu arrayın tamamına ihtiyacımız yok
        // bu yüzden sadece parent category name parent olarak güncelleniyor
        foreach($categories as $key=>$category){
            $categories[$key]["parent"] = $categories[$key]["parent"]["name"] ?? "";
        }

        return datatables()->of($categories)
            ->make(true);
    }
}
