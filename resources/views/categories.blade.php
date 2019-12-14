<div class="card">
    <div class="card-header">Categories</div>
    <div class="card-body">
        @foreach($parentCategories as $category)
        <ul>
            <li>{{$category->name}}</li>
            @if(count($category->subcategory))
                @include('subCategoryList',['subcategories' => $category->subcategory])
            @endif
        </ul>
        @endforeach
    </div>
</div>