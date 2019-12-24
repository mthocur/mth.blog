<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PostView extends Model
{
    /**
     * Defines PostView/user relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Defines PostView/post relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Function for creating pageview log
     *
     * @param \App\Post $post
     * @return void
     */
    public static function createViewLog($post)
    {
        $postsViews = new PostView();
        $postsViews->post()->associate($post->id);
        $postsViews->user()->associate(\Auth::user()->id ?? null);
        $postsViews->session_id = \Request::getSession()->getId() ?? null;
        $postsViews->ip = \Request::getClientIp();
        $postsViews->agent = \Request::header('User-Agent');
        $postsViews->save();
    }

    /**
     * Function for pageviews
     *
     * @return array
     */
    public static function getTodaysHourlyTotalViews()
    {
        $daily_views_array = [];
        for($i=0;$i<24;$i++){
            $daily_views_array[$i] = 0;
        }

        $daily_views = \DB::table("post_views")->get()->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('H');
        })->toArray();

        foreach ($daily_views as $hour => $views) {
            $daily_views_array[$hour] = count($views);
        }
        return $daily_views_array;
    }

    /**
     * Function for unique visitors
     *
     * @return array 
     */
    public static function getUniqueVisitors()
    {
        $daily_views_array = [];
        for($i=0;$i<24;$i++){
            $daily_views_array[$i] = 0;
        }

        $daily_views = \DB::table("post_views")->get()->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('H');
        });

        foreach ($daily_views as $hour => $views) {
            $daily_views_array[$hour] = count($views->groupBy("ip"));
        }
        return $daily_views_array;
    }
}
