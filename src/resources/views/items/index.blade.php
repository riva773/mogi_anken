@extends('layouts.app')

@section('title')
<title>商品一覧</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

@php
$currentTab = $tab ?? request('page','recommend')
@endphp

<nav>
    <a href=" {{ route('items.index',['page' => 'recommend']) }}">
        おすすめ
    </a>

    <a href="{{ route('items.index',['page' => 'mylist']) }}">
        マイリスト
    </a>
</nav>


<div class="item-container">
    @foreach($items as $item)
    <div class=" {{ $item->id }} ">
        <a href="{{ route('items.show', $item->id) }}">
            <img src=" {{ $item->image }}" alt="商品画像" class="item-img">
            <p class="item-name"> {{ $item->name }}</p>
        </a>
        @if($item->status == "sold")
        <span>sold</span>
        @endif

    </div>
    @endforeach
</div>
@endsection