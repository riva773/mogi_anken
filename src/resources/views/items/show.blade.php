@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection
@section('title')
<title>商品詳細</title>
@endsection


@section('content')
<div class="img-container">
    <img src="{{ $item->image }}" alt="商品画像">
</div>
<div class="item-text-container">
    <h2 class="item-name">{{ $item->name }}</h2>
    <small class="item-brand">{{ $item->brand }}</small>
    <p class="item-price">
        {{ $item->price }}
        <span class="tax-label">(税込)</span>
    </p>
    @if(!$likedByMe)
    <form action="{{ route('items.like',['item_id' => $item->id]) }}" method="post">
        @csrf
        <button type="submit">
            <i class="fa-solid fa-star"></i>
        </button>
    </form>
    @else
    <form action="{{ route('items.unlike',['item_id' => $item->id ]) }}" method="post">
        @csrf
        @method('DELETE')
        <button type="submit">
            <i class="fa-solid fa-star"></i>
        </button>
    </form>
    @endif
    <span>{{ $item->likes_count}}</span>
    <i class="fa-solid fa-comment"></i>
    <span>{{ $item->comments_count}}</span>

    <a href="{{ route('orders.create', ['item_id' => $item->id]) }}" class="item-purchase">購入手続きへ</a>
    <div class="item-explanation-container">
        <h3>商品説明</h3>
        <p class="item-explanation">{{ $item->description }}</p>
    </div>
    <div class="item-information">
        <h3>商品の情報</h3>
        <strong>カテゴリ</strong>
        <strong>商品の状態</strong>
        <span>{{ $item->condition}}</span>
    </div>
    <div class="item-comments-container">
        <h3>コメント</h3>
        @forelse ($comments as $comment)
        <p>{{ $comment->content }}</p>
        @empty
        <p>まだコメントはありません。</p>
        @endforelse
        <h4>商品へのコメント</h4>
        <form action="{{ route('item.comments.store',$item->id) }}" method="post">
            @csrf
            <textarea name="content" id=""></textarea>
            <button type="submit">コメントを送信する</button>
        </form>
    </div>
</div>
@endsection