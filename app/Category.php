<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Sluggable;
    
    protected $fillable = [ 'name', 'slug', 'category_id' ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * Defines category/subcategory nested relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Defines category/post relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class,"post_category", "post_id", "category_id");
    }

    /**
     * Defines parent category relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(){
        return $this->belongsTo(Category::class, "category_id","id");
    }

    /**
     * Function for getting all subcategories, works recursively with eager loading
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function children()
    {
        return $this->hasMany(Category::class)->with('children'); // recursive
    }

    /**
     * When deleting
     * Delete nested relations recursively
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            
            $category->children()->get()
                ->each(function ($child) {
                    $child->posts()->delete();
                    $child->delete();
                });
            $category->children()->delete();
            $category->posts()->delete();
        
        });

    }
}
