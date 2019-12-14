@if(isset($activeCategory))
    @php
        $depth++;
    @endphp
    <a href="{{url('blog/category').'/'.$child_category->slug}}" class="list-group-item list-group-item-action {{ ($activeCategory->id == $child_category->id) ? 'active':''}}" style="padding-left:{{$depth*15 + 30}}px">
        {{ $child_category->name }}
    </a>
    @if ($child_category->categories)
        <div class="list-group">
            @foreach ($child_category->categories as $sub_cat)
                @include('inc.subCategories', [
                    'child_category' => $sub_cat,
                    'depth'=>$depth,
                    'activeCategory'=>$activeCategory
                ])
            @endforeach
        </div>
    @endif

@else
    @php
        $depth++;
    @endphp
    <a href="{{url('blog/category').'/'.$child_category->slug}}" class="list-group-item list-group-item-action" style="padding-left:{{$depth*15 + 30}}px">
        {{ $child_category->name }}
    </a>
    @if ($child_category->categories)
        <div class="list-group">

            @foreach ($child_category->categories as $sub_cat)

                @include('inc.subCategories', [
                    'child_category' => $sub_cat,
                    'depth'=>$depth
                ])
            @endforeach
        </div>
    @endif
@endif