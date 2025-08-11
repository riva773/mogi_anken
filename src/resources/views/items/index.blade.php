@extends('layouts.app')
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <title>items</title>
</head>

<body>
    @section('content')
    <div class="item-container">
        @foreach($items as $item)
        <div class=" {{ $item->id }} ">
            <img src=" {{ $item->image }}" alt="商品画像" class="item-img">
            <p class="item-name"> {{ $item->name }}</p>
        </div>
        @endforeach
    </div>
@endsection
</body>

</html>