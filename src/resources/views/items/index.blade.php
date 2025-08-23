@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('title')
<title>商品一覧</title>
@endsection

@section('content')
<div class="tabs-bar">
    <div class="tabs">
        <a href="{{ route('items.index', ['page' => 'recommend', 'q' => request('q')]) }}" class="tab {{ request('page','recommend')==='recommend' ? 'is-active' : '' }}">おすすめ</a>
        <a href="{{ route('items.index', ['page' => 'mylist', 'q' => request('q')]) }}" class="tab {{ request('page')==='mylist' ? 'is-active' : '' }}">マイリスト</a>
    </div>
</div>

<div class="items">
    @forelse($items as $item)
    <a href="{{ route('items.show', $item->id) }}" class="item-card">
        <div class="item-thumb">
            <img src="{{ $item->image }}" alt="{{ $item->name }}">
        </div>
        <div class="item-name">{{ $item->name }}</div>
    </a>
    @empty
    <div class="empty">表示できる商品がありません。</div>
    @endforelse
</div>
@endsection