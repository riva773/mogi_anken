@extends('layouts.app')
@section('title')
<title>商品購入画面</title>
@endsection

@section('content')
<div class="items-container">
    <img src="{{ $item->image }}" alt="商品画像">
    <h2>{{ $item->name }}</h2>
    <p>{{ $item->price }}</p>
</div>
<div class="howto-order">
    <p>支払い方法</p>
    <select name="payment_method" id="payment_method">
        <option value="コンビニ支払い">コンビニ支払い</option>
        <option value="カード支払い">カード支払い</option>
    </select>
</div>
<div class="order-address">
    <p>配送先</p>
    <a href="{{ route('purchase.address.edit', ['item_id' => $item->id]) }}">変更する</a>
    <p>〒 {{ $shipping['postal_code'] }}</p>
    <p>{{ $shipping['address'] }}</p>
    <p>{{ $shipping['building'] }}</p>
</div>

<div class="order-container">
    <p>商品代金</p>
    <p>{{ $item->price }}</p>
    <p>支払い方法</p>
    <p id="select_method"></p>
    <form action="{{ route('orders.store',['item_id' => $item->id ]) }}" method="post">
        @csrf
        <button type="submit">
            購入する
        </button>
    </form>
</div>

<script>
    document.getElementById('payment_method')
        .addEventListener('change', function() {
            document.getElementById('select_method').textContent = this.value;
        });
</script>
@endsection