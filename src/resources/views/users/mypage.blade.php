@extends('layouts.app')
@section('title')
<title>プロフィール</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile_show.css') }}">
@endsection

@section('content')

<div class="profile-container">
    <div class="user-info">
        <div class="avatar">
            <img src="{{ $user->avatar_url }}" alt="プロフィール画像">
        </div>
        <h2 class="user-name">{{ $user->name }}</h2>
        <a href="{{ route('mypage.profile') }}" class="btn-edit">プロフィールを編集</a>
    </div>

    @php $current = request('page', 'sell'); @endphp
    <div class="toppage-list">
        <a href="{{ route('mypage', ['page' => 'sell']) }}" class="tab-link {{ $current==='sell' ? 'is-active' : '' }}">
            出品した商品
        </a>
        <a href="{{ route('mypage', ['page' => 'buy']) }}" class="tab-link {{ $current==='buy' ? 'is-active' : '' }}">
            購入した商品
        </a>
    </div>

    @if($tab === 'sell')
    @if(!empty($items) && $items->count())
    <div class="product-sample-data">
        @foreach($items as $item)
        <a href="{{ route('items.show', ['item_id' => $item->id]) }}" class="product-card">
            <div class="product-thumb">
                <img src="{{ $item->image }}" alt="{{ $item->name }}">
            </div>
            <div class="product-name">{{ $item->name }}</div>
        </a>
        @endforeach
    </div>
    @else
    <p>まだ出品がありません。</p>
    @endif

    @elseif($tab === 'buy')
    @if(!empty($purchases) && $purchases->count())
    <div class="product-sample-data">
        @foreach($purchases as $item)
        <a href="{{ route('items.show', ['item_id' => $item->id]) }}" class="product-card">
            <div class="product-thumb">
                <img src="{{ $item->image }}" alt="{{ $item->name }}">
            </div>
            <div class="product-name">{{ $item->name }}</div>
        </a>
        @endforeach
    </div>
    @else
    <p>まだ購入履歴がありません。</p>
    @endif
    @endif
</div>
@endsection