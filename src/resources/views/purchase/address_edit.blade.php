@extends('layouts.app')
@section('title')
<title>住所変更</title>
@endsection
@section('css')
@endsection
@section('content')
<h2>住所の変更</h2>
<form action="{{ route('purchase.address.update',['item_id' => $item->id]) }}" method="post">
    @csrf
    <label>郵便番号</label>
    <input type="text" name="postal_code" id="">
    <label>住所</label>
    <input type="text" name="address" id="">
    <label>建物名</label>
    <input type="text" name="building" id="">
    <button type="submit">更新する</button>
</form>
@endsection