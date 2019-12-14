@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <!-- Page Content -->
            <div class="container">

                <div class="row">
                    @if(count($posts)>0)
                    @foreach($posts as $post)
                    <div class="col-lg-4 col-sm-6 mb-4">
                        <div class="card h-100">
                            <a href="{{url('blog/post').'/'.$post->slug}}">
                                @if($post->featured_image)
                                <img class="card-img-top" src="{{Storage::url($post->featured_image)}}" alt="">
                                @endif
                            </a>
                            <div class="card-body">
                                <h4 class="card-title">
                                    <a href="{{url('blog/post').'/'.$post->slug}}">{{$post->title}}</a>
                                </h4>
                                @if(count($post->categories)>0)
                                <p>
                                    <ul class="list-inline">
                                        @foreach($post->categories as $category)
                                        <li class="list-inline-item">
                                            <a href="{{url('blog/category').'/'.$category->slug}}">{{$category->name}}</a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </p>
                                @endif
                                <p class="card-text">{{$post->summary}}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @else
                    <div class="col-md-12 text-center text-bold">
                        <h2>No content yet :/</h2>
                    </div>
                    @endif


                </div>
                <!-- /.row -->


                @if(count($posts)>0)
                <!-- Pagination -->
                <div class="pagination justify-content-center">
                    {{$posts->links()}}
                </div>
                @endif

            </div>
            <!-- /.container -->
        </div>
        <div class="col-md-3">
            @include("inc.categoryMenu",[
                'categories' => $categories,
                'activeCategory'=>$activeCategory
            ])
        </div>
    </div>
</div>
@endsection