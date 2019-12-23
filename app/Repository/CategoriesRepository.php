<?php

namespace App\Repository;

use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoriesRepository{

    private $per_page = 12;

    /**
     * Returns categories from cache or database
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function all(){

        return Cache::tags("categories_all")->remember('categories.all' , Carbon::now()->addHour(1), function() {
            return Category::with("children")->whereNull("category_id")->get();
        });

    }

    /**
     * Returns categories by page from cache or database
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function paginate(Request $request){

        $page = $request->has('page') ? $request->query('page') : 1;
        $per_page = $this->per_page;

        return Cache::remember('categories_' . 'page_' . $page, Carbon::now()->addHour(1), function() use( $per_page ) {
            return Category::paginate( $per_page );
        });

    }

    /**
     * Returns category by slug from cache or database
     *
     * @param string $slug
     * @return Illuminate\Database\Eloquent\Collection 
     */
    public function get($slug){
        return Cache::remember('category_'.$slug, Carbon::now()->addHour(1), function() use ($slug){
            return Category::with("children")->where("slug",$slug)->get();
        });
    }


}
