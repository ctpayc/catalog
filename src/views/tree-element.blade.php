<li class="catalog-list" data-id="{{ $category['id'] }}">
    <a class="getdeal_types" href="/catalog/category/getparams/{{ $category['id'] }}" data-type="catalog" data-key="{{ $category['id'] }}">{{ $category['name'] or 'no category name' }}</a>
    <div class="pull-right">
        <a class="editbutton" href="catalog/edit/catalog/{{ $category['id'] }}" data-type="catalog" data-key="{{ $category['id'] }}">
            <span class="glyphicon glyphicon-pencil" style="margin-left:10px;" aria-hidden="true""></span>
        </a>
        <a class="deletebutton" href="catalog/delete/catalog/{{ $category['id'] }}" data-type="catalog" data-key="{{ $category['id'] }}">
            <span class="glyphicon glyphicon-remove text-danger" style="margin-left:10px;" aria-hidden="true"></span>
        </a>
    </div>
</li>

@if (isset($category['children']) && count($category['children']) > 0)
    <ul data-parent="{{ $category['id'] }}" data-id="{{ $category['id'] }}">
        @foreach($category['children'] as $category)
            @include('liteweb-catalog::tree-element', $category)
        @endforeach
    </ul>
@endif