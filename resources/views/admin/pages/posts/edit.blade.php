@extends('layouts.admin')

@section('module')
Posts
@endsection

@section('page')
Edit
@endsection

@section('content')
<h3>
    Edit Post
</h3>
<form method="post" action="{{route('postUpdate',$post->id)}}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-9">
            <div class="form-group">
                <label for="postTitle">Title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="postTitle" name="title" placeholder="Enter a post title." value="{{ $post->title }}">
                @error('title')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="postContent">Content</label>
                <textarea class="form-control @error('content') is-invalid @enderror" name="content" id="postContent">{{$post->content}}</textarea>
                @error('content')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="postSummary">Summary</label>
                <textarea class="form-control @error('summary') is-invalid @enderror" name="summary" id="postSummary" rows="3">{{$post->summary}}</textarea>
                @error('summary')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="col-md-3">

            <div class="form-group">
                <label for="featuredImage">Featured Image</label>
                <div id="preview_img">
                    @if($post->featured_image)
                    <img class="thumb img-fluid" src="{{Storage::url($post->featured_image)}}">
                    @endif
                </div>
                <input type="file" id="featuredImage" name="featured_image" class="form-control @error('featured_image') is-invalid @enderror">
                @error('featured_image')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="categorySelect">Category</label>
                <select class="form-control @error('category_id') is-invalid @enderror" id="categorySelect" name="category_id[]" size="10" multiple>
                    @include("admin.pages.posts.subCategoryOption",[
                    "categories"=>$categories,
                    "depth"=>0,
                    "selected"=>$selected])
                </select>
                @error('category_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>

</form>
@endsection

@section("scriptSrc")
<script src="{{ asset('node_modules/tinymce/tinymce.js') }}"></script>
@endsection


@section("afterScript")
tinymce.init({
selector:'textarea#postContent',
height: 300
});
$(function(){
$(document).on('change', '#featuredImage', function(){
if (window.File && window.FileReader && window.FileList && window.Blob) //check File API supported browser
{
// Browser supports File API

var data = $(this)[0].files; //this file data

$.each(data, function(index, file){

//check supported file type
if(/(\.|\/)(gif|jpe?g|png)$/i.test(file.type)){

//new filereader
var fRead = new FileReader();

//trigger function on successful read
fRead.onload = (function(file){
return function(e) {
//create image element
var img = $('<img />').addClass('thumb').addClass('img-fluid').attr('src', e.target.result);
//append image to output element
$('#preview_img').html(img);
};
})(file);
//URL representing the file's data.
fRead.readAsDataURL(file);
}
});

}else{
//if File API is absent
alert("Your browser doesn't support File API!");
}
});
});
@endsection