<?php

namespace App\Repository;

use App\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostsRepository{

    private $per_page = 12;

    /**
     * Returns posts by page from cache or database
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function paginate(Request $request){
        $page = $request->has('page') ? $request->query('page') : 1;
        $per_page = $this->per_page;

        return Cache::tags("posts")->remember('posts_' . 'page_' . $page, Carbon::now()->addHour(1), function() use( $per_page ) {
            return Post::paginate( $per_page );
        });
    }

    /**
     * Returns post by slug from cache or database
     *
     * @param string $slug
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function get($slug){
        return Cache::tags("post_slug")->remember('post_' . $slug, Carbon::now()->addHour(1), function() use( $slug ) {
            return Post::where("slug", $slug)->get();
        });
    }

    /**
     * Returns posts in category
     *
     * @param Illuminate\Http\Request $request
     * @param App\Category $category
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function postIn(Request $request, $category){

        // nested category array to standart array
        $cats_array = categoryThreeToArray($category);
        // get category id nums
        $cat_ids = array_column($cats_array,"id");
        $page = $request->has('page') ? $request->query('page') : 1;
        $per_page = $this->per_page;

        return Cache::tags('category_' . $category->first()->slug . '_posts')->remember('posts_category'.$category->first()->slug. '_page_' . $page, Carbon::now()->addHour(1), function() use( $per_page , $cat_ids) {
            return Post::whereHas("categories", function($query) use ($cat_ids) {
                $query->whereIn("categories.id",$cat_ids);
            })->paginate($per_page);
        });
    }

}
