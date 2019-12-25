@extends('layouts.app')

@section('content')
<!-- Page Content -->
<div class="container">

    <div class="row">
        <div class="col-md-9">

            <!-- Post Heading -->
            <h1 class="my-4">{{$post->title}}</h1>

            <!-- Post Body -->
            <div class="row">
                @if($post->featured_image)
                <div class="col-md-12">
                    <img class="img-fluid" src="{{Storage::url($post->featured_image)}}" alt="{{$post->title}}">
                </div>
                @endif
                <div class="col-md-12">
                    {!! $post->content !!}
                </div>
            </div>

            @auth
                <!-- Comment Form -->
                <div class="row">
                    <div class="col-md-12">
                        <h2>Write Comment</h2>
                        @include("inc.comments.form",$post)
                    </div>
                </div>
            @endauth

            @if(count($post->comments)>0)
                <!-- Comments -->
                @include('inc.comments.list',['comments'=>$comments])
            @endif

            @if(count($related)>0)
                <!-- Related Posts -->
                <h3 class="my-4">Related Posts</h3>

                <div class="row">
                    @foreach($related as $related_post)
                        <div class="col-lg-3 col-sm-6 mb-4">
                            <div class="card h-100">
                                <a href="{{url('blog/post').'/'.$post->slug}}">
                                    @if($related_post->featured_image)
                                    <img class="card-img-top" src="{{Storage::url($related_post->featured_image)}}" alt="">
                                    @endif
                                </a>
                                <div class="card-body">
                                    <h4 class="card-title">
                                        <a href="{{url('blog/post').'/'.$related_post->slug}}">{{$related_post->title}}</a>
                                    </h4>
                                    @if(count($related_post->categories)>0)
                                    <p>
                                        <ul class="list-inline">
                                            @foreach($related_post->categories as $category)
                                            <li class="list-inline-item">
                                                <a href="{{url('blog/category').'/'.$category->slug}}">{{$category->name}}</a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </p>
                                    @endif
                                    <p class="card-text">{{$related_post->summary}}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
        <div class="col-md-3">
            @include("inc.categories.menu",[ 'categories' => $categories ])
        </div>
    </div>
</div>
<!-- /.container -->

@endsection

@section("script")

$(".btn-reply").click(function(event){
    // get comment user name
    var replyto = $(this).parent().parent().find(".comment-author").html();
    var replyToComment =$(this).closest(".comment").attr("data-id");
    $('#commentForm .replyToUser').html(replyto);

    $('#commentForm').find(".replyToAlert").css("display","block");

    // if reply input exists
    if($('#commentForm').children('input.comment_data').length > 0){
        $('#commentForm').children('input.comment_data').val($(this).closest(".comment").attr("data-id"));
    }else{
        $('<input>').attr({
            type: 'hidden',
            id: 'commentData',
            name: 'comment_id',
            class: 'comment-data',
            value: replyToComment
        }).appendTo('#commentForm');
    }

    $([document.documentElement, document.body]).animate({
        scrollTop: $("#commentForm").offset().top - 100
    }, 500);

});

$(document).on('click', '#replyCancelButton', function(){
    $('input.comment_data').remove();
    $('#commentForm').find(".replyToAlert").css("display","none");
});


@endsection
