@extends('layouts.app')

@section('content')
<h2>プロフィール設定</h2>

<form action="{{ route('mypage.update') }}" method="post">
    @csrf
    @method('PUT')

    <input type="file" name="avatar" id="avatar">
    <a href="#">画像を選択する</a>
    <label for="name">ユーザー名</label>
    <input type="text" name="name" id="name">
    <label for="postal_code">郵便番号</label>
    <input type="text" name="postal_code" id="postal_code">
    <label for="address">住所</label>
    <input type="text" name="address" id="address">
    <label for="building">建物名</label>
    <input type="text" name="building" id="building">
    <button type="submit">更新する</button>
</form>
@endsection