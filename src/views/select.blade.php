@if (isset($categories) && count($categories) > 0)
    <select name="parent" data-parent="0" class="form-control">
        <option value="0">Нет родителя</option>
        @each('liteweb-catalog::select-element', $categories, 'category', 'liteweb::none', 'separator')
    </select>
@endif