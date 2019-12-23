<?php

namespace App\Http\Controllers;

use App\Post;
use App\PostView;
use Illuminate\Http\Request;
use Facades\App\Repository\PostsRepository;
use Facades\App\Repository\CategoriesRepository;

class BlogController extends Controller
{
    /**
     * Blog posts list page.
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function blog(Request $request)
    {
        $posts = PostsRepository::paginate($request);

        $categories = CategoriesRepository::all();

        return view("blog")->with(["posts"=>$posts,"categories"=>$categories]);
    }

    /**
     * List posts by category.
     *
     * @param Illuminate\Http\Request $request
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function category(Request $request, $slug)
    {
        $categories = CategoriesRepository::all();

        $category = CategoriesRepository::get($slug);

        if(!$category){
            return redirect(route("404"));
        }
        
        // get posts related to this category
        $posts = PostsRepository::postIn($request, $category);

        return view("category")->with(["posts" => $posts,"categories"=>$categories,"activeCategory"=>$category->first()]);

    }

    /**
     * Show the post by slug.
     *
     * @return \Illuminate\View\View
     */
    public function post($slug)
    {
        try{
            $categories = CategoriesRepository::all();

            $post = PostsRepository::get($slug);

            PostView::createViewLog($post->first());

            if(!$post){
                return redirect(route("404"));
            }

            $related = Post::orderByRaw('RAND()')->where("id","!=",$post->first()->id)->take(4)->get();
        } catch (\Exception $ex) {
            return redirect(route("404"));
        }
        return view("detail")->with([
            "post" => $post->first(),
            "categories"=>$categories,
            "related"=>$related
        ]);

    }
    
}
