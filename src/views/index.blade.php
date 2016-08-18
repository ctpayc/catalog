<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Каталог | Категории</title>
</head>
<body>
    <div class="container">
        <div class="content">
            <div class="title">Все категории</div>
            <div class="box-container">
                @if (isset($categories))
                    @foreach ($categories as $category)
                        <button href="/catalog/{{ $category->id or '0' }}">{{ $category->name or 'noname' }}</button>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</body>