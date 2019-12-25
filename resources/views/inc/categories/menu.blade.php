@if(count($categories)>0)
<div class="list-group">
    <a class="list-group-item text-bold"><strong>Categories</strong></a>
    @foreach ($categories as $category)
    <a href="{{url('blog/category').'/'.$category->slug}}" class="list-group-item list-group-item-action {{ (isset($activeCategory) && $category->id == $activeCategory->id) ? 'active':'' }}" style="padding-left:30px">
        {{$category->name}}
    </a>
    <div class="list-group">
        @foreach ($category->children as $sub_cat)
        @include('inc.categories.menu_children', [
        'child_category' => $sub_cat,
        "depth"=>0
        ])
        @endforeach
    </div>
    @endforeach
</div>
@else
<div class="col-md-12 text-center text-bold">
    <h2>No categories yet :/</h2>
</div>
@endif
