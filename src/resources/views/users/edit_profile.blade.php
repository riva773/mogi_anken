@extends('layouts.app')

@section('title')
<title>プロフィール編集</title>
@endsection

@section('content')
<h2>プロフィール設定</h2>

<form action="{{ route('mypage.profile.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <input type="file" name="avatar" id="avatar">
    <a href="#">画像を選択する</a>
    <label for="name">ユーザー名</label>
    <input type="text" name="name" id="name" placeholder="{{$user->name}}">
    <label for="postal_code">郵便番号</label>
    <input type="text" name="postal_code" id="postal_code" placeholder="{{$user->postal_code}}">
    <label for="address">住所</label>
    <input type="text" name="address" id="address" placeholder="{{$user->address}}">
    <label for="building">建物名</label>
    <input type="text" name="building" id="building" placeholder="{{$user->building}}">
    <button type="submit">更新する</button>
</form>
@endsection