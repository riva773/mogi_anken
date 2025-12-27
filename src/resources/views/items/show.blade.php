@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('title')
<title>商品詳細</title>
@endsection

@section('content')
<div class="show">
    <div class="show__grid">
        <div class="img-container">
            <img src="{{ $item->image }}" alt="商品画像">
        </div>

        <div class="item-text-container">
            <h2 class="item-name">{{ $item->name }}</h2>
            <small class="item-brand">{{ $item->brand }}</small>

            <p class="item-price">
                ¥{{ number_format($item->price) }}
                <span class="tax-label">(税込)</span>
            </p>

            <div class="item-stats">
                <div class="like-block">
                    @if(!$likedByMe)
                    <form action="{{ route('items.like', ['item_id' => $item->id]) }}" method="post" class="like-form">
                        @csrf
                        <button type="submit"
                            class="icon-btn"
                            data-liked="false"
                            aria-pressed="false">
                            <i class="fa-regular fa-heart"></i>
                        </button>
                    </form>
                    @else
                    <form action="{{ route('items.unlike', ['item_id' => $item->id]) }}" method="post" class="like-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="icon-btn is-active"
                            data-liked="true"
                            aria-pressed="true">
                            <i style=color:red; class="fa-solid fa-heart"></i>
                        </button>
                    </form>
                    @endif
                    <span class="count" data-testid="likes-count">{{ $item->likes_count }}</span>
                </div>

                <div class="comment-block">
                    <i class="fa-regular fa-comment"></i>
                    <span class="count" data-testid="comments-count">{{ $item->comments_count }}</span>
                </div>
            </div>

            <a href="{{ route('orders.create', ['item_id' => $item->id]) }}" class="item-purchase">購入手続きへ</a>

            <div class="item-explanation-container">
                <h3>商品説明</h3>
                <p class="item-explanation">{{ $item->description }}</p>
            </div>

            <div class="item-information">
                <h3>商品の情報</h3>
                <div class="info-row">
                    <strong>カテゴリ</strong>
                    <div class="chips">
                        @foreach(($item->categories ?? []) as $cat)
                        <span class="chip">{{ $cat }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="info-row">
                    <strong>商品の状態</strong>
                    <span class="chip">{{ $item->condition }}</span>
                </div>
            </div>

            <div class="item-comments-container">
                <h3>コメント(<span data-testid="comments-count">{{ $item->comments_count }}</span>)</h3>

                @forelse ($comments as $comment)
                <div class="comment">
                    <div class="avatar"></div>
                    <div class="comment__body">
                        <div class="author">{{ optional($comment->user)->name ?? 'ユーザー' }}</div>
                        <p class="content">{{ $comment->content }}</p>
                    </div>
                </div>
                @empty
                <p class="no-comments">まだコメントはありません。</p>
                @endforelse

                <h4>商品へのコメント</h4>
                <form action="{{ route('item.comments.store', $item) }}" method="post" class="comment-form">
                    @csrf
                    @include('partials.errors')
                    <textarea name="content" placeholder="こちらにコメントが入ります。"></textarea>
                    <button type="submit" class="btn-send">コメントを送信する</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection