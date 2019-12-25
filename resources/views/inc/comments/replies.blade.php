@foreach ($replies as $reply)
<!-- Comment replies -->
<div class="comment card card-inner" data-id="{{$reply->id}}">
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <img src="https://image.ibb.co/jw55Ex/def_face.jpg" class="img img-rounded img-fluid"/>
                <p class="text-secondary text-center">{{$reply->created_at->diffForHumans()}}</p>
            </div>
            <div class="col-md-10">
                <p>
                    <a href="#"><strong class="comment-author">{{$reply->user->name}}</strong></a>
                    <span class="float-right">
                        <a class="float-right btn btn-outline-primary btn-xs ml-2 btn-reply"><i class="fa fa-reply"></i> Reply</a>
                    </span>
                </p>
                <p>{{$reply->body}}</p>
            </div>
        </div>
        @if ($reply->replies)
            @include('inc.comments.replies',['replies'=>$reply->replies])
        @endif
    </div>
</div>
@endforeach
