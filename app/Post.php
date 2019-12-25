<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Sluggable;

    protected $guarded = [];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * Relation for post/category relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class,"post_category", "post_id", "category_id");
    }

    /**
     * Relation for post/category relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Post/Comments Polymorphic relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments(){
        return $this->morphMany(Comment::class,"commentable");
    }

    /**
     * When model is booting
     * Delete relations recursively
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {

            $post->categories()->sync([]);
        });
    }

}
