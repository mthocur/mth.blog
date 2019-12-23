<?php

namespace App\Http\Controllers;

use App\Post;
use App\Category;
use App\PostView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class BlogController extends Controller
{
    /**
     * Blog posts list page.
     *
     * @return \Illuminate\View\View
     */
    public function blog(Request $request)
    {
        $per_page = 12;
        $page = $request->has('page') ? $request->query('page') : 1;

        $posts = Cache::remember('posts_' . 'page_' . $page, Carbon::now()->addHour(1), function() use( $per_page ) {
            return Post::paginate( $per_page );
        });

        $categories = Cache::remember('categories.all', Carbon::now()->addHour(1), function() {
            return Category::with("children")->whereNull("category_id")->get();
        });

        return view("blog")->with(["posts"=>$posts,"categories"=>$categories]);
    }

    /**
     * List posts by category.
     *
     * @return \Illuminate\View\View
     */
    public function category($slug)
    {
        $categories = Cache::remember('categories.all', Carbon::now()->addHour(1), function() {
            return Category::with("children")->whereNull("category_id")->get();
        });

        $category = Category::with("children")->where("slug",$slug)->get();
        //dd($category->toArray());
        if(!$category){
            return redirect(route("404"));
        }
        
        // nested category array to standart array
        $cats_array = $this->categoryThreeToArray($category);
        // get category id nums
        $cat_ids = array_column($cats_array,"id");

        // get posts related to this category
        $posts = Post::whereHas("categories", function($query) use ($cat_ids) {
            $query->whereIn("categories.id",$cat_ids);
        })->paginate(12);

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
            $categories = Cache::remember('categories.all', Carbon::now()->addHour(1), function() {
                return Category::with("children")->whereNull("category_id")->get();
            });

            $post = Post::where("slug",$slug)->get();

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
    
    /**
     * nested category three array to standart array
     *
     * @param mixed $array
     * @param int $parent
     * @param array $empty
     * @return array
     */
    private function categoryThreeToArray($array, $parent = null, &$empty = [])
    {
        foreach ($array as $key => $row) {
            
            $empty[] = [
                "id" => $row->id,
                "category_id" => $parent
            ];

            if (count($row->children) > 0)
                $this->categoryThreeToArray($row->children, $row->id, $empty);
        }

        return $empty;
    }

}
