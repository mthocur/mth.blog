@extends('layouts.app')

@section('content')
<!-- Page Content -->
<div class="container">

    <div class="row">
        <div class="col-md-9">

            <!-- Portfolio Item Heading -->
            <h1 class="my-4">{{$post->title}}
            </h1>

            <!-- Portfolio Item Row -->
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
            <!-- /.row -->

            <!-- Related Posts Row -->
            <h3 class="my-4">Related Posts</h3>

            <div class="row">

                @if(count($related)>0)
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
                @endif
            </div>


            <!-- /.row -->
        </div>
        <div class="col-md-3">
            @include("inc.categoryMenu",[
            'categories' => $categories
            ])
        </div>
    </div>
</div>
<!-- /.container -->

@endsection