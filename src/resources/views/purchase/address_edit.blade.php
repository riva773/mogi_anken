@extends('layouts.app')
@section('title')
<title>住所変更</title>
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection
@section('content')
@include('partials.errors')

<h2 class="address-title">住所の変更</h2>

<form action="{{ route('purchase.address.update',['item_id' => $item->id]) }}" method="post" class="address-form">
    @csrf

    <label class="form-label">郵便番号</label>
    <input type="text" name="postal_code" value="{{ old('postal_code', $shipping['postal_code'] ?? '' )}}" class="form-input">

    <label class="form-label">住所</label>
    <input type="text" name="address" value="{{ old('address', $shipping['address'] ?? '') }}" class="form-input">

    <label class="form-label">建物名</label>
    <input type="text" name="building" value="{{ old('building',$shipping['building'] ?? '' )}}" class="form-input">

    <button type="submit" class="btn-submit">更新する</button>
</form>
@endsection