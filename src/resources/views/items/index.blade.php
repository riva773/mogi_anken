@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="item-container">
    @foreach($items as $item)
    <div class=" {{ $item->id }} ">
        <a href="{{ route('items.show', $item->id) }}">
            <img src=" {{ $item->image }}" alt="商品画像" class="item-img">
            <p class="item-name"> {{ $item->name }}</p>
        </a>
    </div>
    @endforeach
</div>
@endsection