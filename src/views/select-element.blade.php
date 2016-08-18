<option value="{{ $category['id'] }}">
    {{ $category['name'] or 'no category name' }}
</option>

@if (isset($category['children']) && count($category['children']) > 0)
        @foreach($category['children'] as $category)
            @include('liteweb-catalog::select-element', $category)
        @endforeach
@endif