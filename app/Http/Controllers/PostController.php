<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Category;
use App\Post;
use App\PostView;
use Facades\App\Repository\CategoriesRepository;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    /**
     * Display a listing of the post.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.pages.posts.list");
    }

    /**
     * Show the form for creating a new post.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = CategoriesRepository::all();

        return view("admin.pages.posts.create")->with(["categories" => $categories]);
    }

    /**
     * Store a newly created post in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $validation = Validator::make($request->except('_token'), [
            'title' => 'required|min:3|max:150',
            'content' => 'required',
            'summary' => 'required|min:3|max:250',
            'featured_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validation->fails()) {
            return back()->withErrors($validation);
        }

        try{

            $post = new Post();
            $post->title = $request->input("title");
            $post->content = $request->input("content");
            $post->summary = $request->input("summary");
            $post->user()->associate($request->user());
            if ($files = $request->file('featured_image')) {
                $image = $request->file('featured_image')->store('public/uploads');
                $post->featured_image = $image;
            }
            $post->save();
            $post->categories()->sync($request->input("category_id"), false);
            // flush cache of selected categories
            foreach($request->input("category_id") as $cat_id){
                Cache::tags('category_' . Category::where("id",$cat_id)->get()->first()->slug . '_posts')->flush();
            }
            Cache::tags("posts")->flush();

        }catch(\Exception $ex){
            dd($ex);
            return redirect(route("postMain"))->with("error", "Some errors occured.");
        }


        return redirect(route("postMain"))->with("success", "Post successfully inserted.");
    }

    /**
     * Show the form for editing the specified post.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = CategoriesRepository::all();

        $post = Post::where("id", $id)->get()->first();

        // get only category ids of post (for using in_array in blade template)
        $key = "id";
        $category_ids = array_map(function ($item) use ($key) {
            return $item["id"];
        }, $post->categories->toArray());

        if (is_null($post)) {
            return back()->with("error", "Girdi bulunamadı!");
        }

        $selected = array();
        foreach($category_ids as $cat_id){
            if(in_array($cat_id, $category_ids)){
                $selected[] = $cat_id;
            }
        }

        return view("admin.pages.posts.edit")->with([
            "post" => $post,
            "categories" => $categories,
            "selected"=> $selected
        ]);
    }

    /**
     * Update the specified post in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->except('_token'), [
            'title' => 'required|min:3|max:150',
            'content' => 'required',
            'summary' => 'required|min:3|max:250',
            'featured_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validation->fails()) {
            return back()->withErrors($validation);
        }

        try {

            $post = Post::find($id);
            $post->title = $request->input("title");
            $post->content = $request->input("content");
            $post->summary = $request->input("summary");
            $post->user()->associate($request->user());
            if ($files = $request->file('featured_image')) {
                Storage::delete($post->featured_image);
                $image = $request->file('featured_image')->store('public/uploads');
                $post->featured_image = $image;
            }
            $post->save();
            $post->categories()->sync($request->input("category_id"), true);

            Cache::tags("post_slug")->flush();
            // flush cache of selected categories
            foreach($request->input("category_id") as $cat_id){
                Cache::tags('category_' . Category::where("id",$cat_id)->get()->first()->slug . '_posts')->flush();
            }
            Cache::tags("posts")->flush();

        } catch (\Exception $ex) {
            return redirect(route("postMain"))->with("error", "Some errors occured.");
        }

        return redirect(route("postMain"))->with("success", "Post successfully inserted.");
    }

    /**
     * Remove the specified post from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $post = Post::find($id);
            Storage::delete($post->featured_image);
            $post->delete();
        } catch (\Exception $ex) {
            return redirect(route("postMain"))->with("error", "Some errors occured!");
        }
        return redirect(route("postMain"))->with("success", "Post successfully deleted.");
    }

    /**
     * Function for datatable list ajax requests
     *
     * @return string string contains datatables json data
     */
    public function ajax()
    {

        $posts = Post::all();
        foreach ($posts as $post) {
            $post["views"] = PostView::where("post_id",$post->id)->count();
        }

        return datatables()->of($posts)
            ->make(true);
    }

}
