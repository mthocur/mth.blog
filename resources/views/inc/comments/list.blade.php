<h2>Comments</h2>
@foreach ($comments as $comment)
<div class="comment card" data-id="{{$comment->id}}">
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <img src="https://image.ibb.co/jw55Ex/def_face.jpg" class="img img-rounded img-fluid"/>
                <p class="text-secondary text-center">{{$comment->created_at->diffForHumans()}}</p>
            </div>
            <div class="col-md-10">
                <p>
                    <a class="float-left" href="#"><strong class="comment-author">{{$comment->user->name}}</strong></a>
                    <span class="float-right">
                        <a class="float-right btn btn-outline-primary btn-xs ml-2 btn-reply">Reply</a>
                    </span>
                </p>
                <div class="clearfix"></div>
                <p>{{$comment->body}}</p>
            </div>
        </div>
        @if ($comment->replies)
            @include('inc.comments.replies',['replies'=>$comment->replies])
        @endif

    </div>
</div>
@endforeach

@if($comments->lastPage() > 1)
<div class="row ">
    <div class="col-md-12">
        {{$comments->links()}}
    </div>
</div>
@endif
