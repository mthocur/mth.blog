@if(isset($category))
    @foreach($categories as $cat)
    <option value="{{$cat->id}}" {{ ($selected == $cat->category_id ? 'disabled':'') }} {{ ($category->category_id == $cat->id ? 'selected':'') }}>
        {{str_repeat("-",$depth).$cat->name}}
    </option>
    @if(count($cat->children) > 0)
        @php
            $depth++;
        @endphp
        @include("admin.pages.categories.subCategoryOption",[
            "categories"=>$cat->children,
            "depth"=>$depth,
            "selected"=>$selected,
            "editing"=>$category->category_id
        ])
        @endif
    @endforeach
@else
    @foreach($categories as $cat)
    <option value="{{$cat->id}}" >
        {{str_repeat("-",$depth).$cat->name}}
    </option>
    @if(count($cat->children) > 0)
        @php
            $depth++;
        @endphp
        @include("admin.pages.categories.subCategoryOption",[
            "categories"=>$cat->children,
            "depth"=>$depth,
            "selected"=>$selected,
            "editing"=>null
        ])
        @endif
    @endforeach
@endif