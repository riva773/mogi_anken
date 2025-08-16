@extends('layouts.app')
@section('title')
<title>プロフィール</title>
@endsection

@section('content')

<img src="{{ $user->avatar_url}}" alt="プロフィール画像">
<h2>{{ $user->name }}</h2>
<a href="{{ route('mypage.profile') }}">プロフィールを編集</a>

@php $current = request('page', 'sell'); @endphp
<div>
    <a href="{{ route('mypage', ['page' => 'sell']) }}">
        出品した商品
    </a>
    <a href="{{ route('mypage', ['page' => 'buy']) }}">
        購入した商品
    </a>
</div>

@if($tab === 'sell')
@if(!empty($items) && $items->count())
<div>
    @foreach($items as $item)
    <a href="{{ route('items.show', $item) }}">
        <div>
            <img src="{{ $item->image }}" alt="{{ $item->name }}">
        </div>
        <div>{{ $item->name }}</div>
    </a>
    @endforeach
</div>
@else
<p>まだ出品がありません。</p>
@endif

@elseif($tab === 'buy')
@if(!empty($purchases) && $purchases->count())
<div>
    @foreach($purchases as $item)
    <a href="{{ route('items.show', $item) }}">
        <div>
            <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
        </div>
        <div>{{ $item->name }}</div>
    </a>
    @endforeach
</div>
@else
<p>まだ購入履歴がありません。</p>
@endif
@endif
</div>
@endsection