<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Facades\App\Repository\PostsRepository;

class CommentController extends Controller
{
    public function make(Request $request){
        $validation = Validator::make($request->except("_token"),[
            "body"=>"string|required|min:4|max:500",
            "reply"=>"numeric|exists:comments,id|nullable"
        ]);

        if($validation->fails()){
            return redirect()->back()->withErrors($validation);
        }

        $post = Post::findOrFail($request->post_id);

        try{
            $post->comments()->create([
                'body'=>$request->input('body'),
                'comment_id'=>$request->input('comment_id') ?? null,
                'user_id'=>auth()->user()->id,
                'status'=>0
            ]);
        }catch(\Exception $ex){
            return redirect()->back()->with("error","Some errors occured!");
        }

        Cache::tags("comments_post")->flush();

        return redirect()->back()->with("success","Comment successfully created!");

    }

    /**
     * Display a listing of the comments.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.pages.comments.list");
    }

    /**
     * Show the form for editing the specified comment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $comment = Comment::where("id", $id)->get()->first();

        if (is_null($comment)) {
            return back()->with("error", "Couldn't find content!");
        }

        return view("admin.pages.comments.edit")->with([
            "comment" => $comment
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
            'body' => 'required'
        ]);

        if ($validation->fails()) {
            return back()->withErrors($validation);
        }

        try {

            $comment = Comment::find($id);
            $comment->body = $request->input("body");
            $comment->status = $request->input("status");
            $comment->save();

            Cache::tags("comments_post")->flush();

        } catch (\Exception $ex) {
            return redirect(route("commentMain"))->with("error", "Some errors occured.");
        }

        return redirect(route("commentMain"))->with("success", "Comment successfully inserted.");
    }

    /**
     * Remove the specified comment from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $comment = Comment::find($id);
            $comment->delete();
            Cache::tags("comments_post")->flush();
        } catch (\Exception $ex) {
            return redirect(route("commentMain"))->with("error", "Some errors occured!");
        }
        return redirect(route("commentMain"))->with("success", "Comment successfully deleted.");
    }

    /**
     * Function for datatable list ajax requests
     *
     * @return string string contains datatables json data
     */
    public function ajax()
    {

        $comments = Comment::all()->map(function($comment){
            return [
                "id"=>$comment->id,
                "owner"=>$comment->user->name,
                "body"=>$comment->body,
                "status"=>$comment->status==1 ? "Active":"Deactive",
                "created_at"=>date('d M Y - H:i:s', $comment->created_at->timestamp),
                "updated_at"=>date('d M Y - H:i:s', $comment->updated_at->timestamp)
            ];
        });
        // dd($comments);

        return datatables()->of($comments)
            ->make(true);
    }
}
