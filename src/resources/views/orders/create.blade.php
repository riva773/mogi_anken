@extends('layouts.app')
@section('title')
<title>商品購入画面</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/order.css') }}">
@endsection

@section('content')
@include('partials.errors')

<form action="{{ route('orders.store', ['item_id' => $item->id]) }}" method="post" class="buy-form">
    @csrf

    <div class="order">
        <div class="order__left">
            <div class="items-container">
                <div class="item-thumb">
                    <img src="{{ $item->image }}" alt="商品画像">
                </div>
                <div class="item-meta">
                    <h2 class="item-name">{{ $item->name }}</h2>
                    <p class="item-price">¥{{ number_format($item->price) }}</p>
                </div>
            </div>

            <hr class="divider">

            <div class="howto-order">
                <p class="section-title">支払い方法</p>
                <select name="payment_method" id="payment_method" class="select-payment">
                    <option value="" hidden {{ old('payment_method')==='' ? 'selected' : '' }}>選択してください</option>
                    <option value="コンビニ支払い" {{ old('payment_method')==='コンビニ支払い' ? 'selected' : '' }}>コンビニ支払い</option>
                    <option value="カード支払い" {{ old('payment_method')==='カード支払い'   ? 'selected' : '' }}>カード支払い</option>
                </select>
                <hr class="divider">
            </div>

            <div class="order-address">
                <div class="address-head">
                    <p class="section-title">配送先</p>
                    <a href="{{ route('purchase.address.edit', ['item_id' => $item->id]) }}" class="address-edit">変更する</a>
                </div>

                @php
                $zipRaw = $shipping['postal_code'] ?? '';
                $zipDigits = preg_replace('/\D/', '', (string)$zipRaw);
                if ($zipDigits !== '' && strlen($zipDigits) === 7) {
                $zipFormatted = substr($zipDigits, 0, 3) . '-' . substr($zipDigits, 3);
                } elseif (is_string($zipRaw) && preg_match('/^\d{3}-\d{4}$/', $zipRaw)) {
                $zipFormatted = $zipRaw;
                } else {
                $zipFormatted = '未設定';
                }
                @endphp

                <div class="address-body">
                    <p class="zip">〒 {{ $zipFormatted }}</p>
                    <p class="addr">{{ $shipping['address'] ?? '' }}</p>
                    <p class="bldg">{{ $shipping['building'] ?? '' }}</p>
                </div>

                <input type="hidden" name="shipping[name]" value="{{ $shipping['name'] ?? '' }}">
                <input type="hidden" name="shipping[postal_code]" value="{{ $shipping['postal_code'] ?? '' }}">
                <input type="hidden" name="shipping[address]" value="{{ $shipping['address'] ?? '' }}">
                <input type="hidden" name="shipping[building]" value="{{ $shipping['building'] ?? '' }}">
            </div>
        </div>

        <div class="order__right">
            <div class="order-container">
                <div class="summary-row">
                    <span>商品代金</span>
                    <span>¥{{ number_format($item->price) }}</span>
                </div>
                <div class="summary-row">
                    <span>支払い方法</span>
                    <span id="select_method">{{ old('payment_method', '未選択') }}</span>
                </div>
            </div>

            <button type="submit" class="btn-buy">購入する</button>
        </div>
    </div>
</form>

<script>
    document.getElementById('payment_method')?.addEventListener('change', function() {
        document.getElementById('select_method').textContent = this.value || '未選択';
    });
</script>
@endsection