<?php

namespace App\Repository;

use App\Comment;
use Carbon\Carbon;
use Facades\App\Repository\PostsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CommentsRepository{

    private $per_page = 4;
    /**
     * Returns comments on a post from cache or database
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getPostComments(Request $request,$postSlug){

        $page = $request->has('page') ? $request->query('page') : 1;
        $per_page = $this->per_page;
        $post = PostsRepository::get($postSlug);

        return Cache::tags("comments_post")->remember('comments_post_'.$post->first()->id . '_page_' . $page, Carbon::now()->addHour(1), function() use( $per_page, $post ) {
            return Comment::with("replies")->where("commentable_id",$post->first()->id)->whereNull("comment_id")->where("status",1)->paginate($per_page);
        });

    }

}
