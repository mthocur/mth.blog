@extends('layouts.admin')

@section('module')
    Categories
@endsection

@section('page')
    Add
@endsection

@section('content')
<h3>
    Add Category
</h3>
<form method="post" action="{{route('categoryStore')}}">
    @csrf
    <div class="form-group">
        <label for="categoryName">Name</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="categoryName" name="name" placeholder="Enter a category name." value="{{ old('name') }}">
        @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group">
        <label for="categorySelect">Parent Category</label>
        <select class="form-control @error('category_id') is-invalid @enderror" id="categorySelect" name="category_id" aria-describedby="parentHelp">
            <option value="0">No Parent</option>
            @include("admin.pages.categories.subCategoryOption",[
            "categories"=>$categories,
            "depth"=>0,
            "selected"=>old("category_id")])
        </select>
        @error('category_id')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection