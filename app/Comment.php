<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    /**
     * Comments Polymorphic relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable(){
        return $this->morphTo();
    }

    /**
     * Comment/User relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Comment/Parent relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(){
        return $this->belongsTo(Comment::class);
    }

    /**
     * Comment/Reply relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reply(){
        return $this->hasMany(Comment::class);
    }

    /**
     * Get comments all replies recursively
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function replies(){
        return $this->hasMany(Comment::class)->with('replies'); // recursive
    }

}
