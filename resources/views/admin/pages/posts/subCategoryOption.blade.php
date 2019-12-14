@if(isset($post))
@foreach($categories as $cat)
<option value="{{$cat->id}}" {{ ($selected == $cat->category_id ? 'disabled':'') }} {{ (in_array($cat->id,$selected) ? 'selected':'') }}>
    {{str_repeat("-",$depth).$cat->name}}
</option>
@if(count($cat->children) > 0)
@php
$depth++;
@endphp
@include("admin.pages.posts.subCategoryOption",[
"categories"=>$cat->children,
"depth"=>$depth,
"selected"=>$selected
])
@endif
@endforeach
@else
@foreach($categories as $cat)
<option value="{{$cat->id}}" {{ ($selected == $cat->category_id ? 'disabled':'') }} {{ (in_array($cat->id,$selected) ? 'selected':'') }}>
    {{str_repeat("-",$depth).$cat->name}}
</option>
@if(count($cat->children) > 0)
@php
$depth++;
@endphp
@include("admin.pages.posts.subCategoryOption",[
"categories"=>$cat->children,
"depth"=>$depth,
"selected"=>$selected
])
@endif
@endforeach
@endif