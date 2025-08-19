@extends('layouts.app')

@section('title')
<title>プロフィール編集</title>
@endsection

@section('content')
@include('partials.errors')
<h2>プロフィール設定</h2>

<form action="{{ route('mypage.profile.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <input type="file" name="avatar" id="avatar">
    <a href="#">画像を選択する</a>
    @error('avatar')
    @enderror
    <label for="name">ユーザー名</label>
    <input type="text" name="name" id="name" value="{{ old('name',$user->name) }}">
    @error('name')
    @enderror
    <label for="postal_code">郵便番号</label>
    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
    @error('postal_code')
    @enderror
    <label for="address">住所</label>
    <input type="text" name="address" id="address" value="{{ old('address',$user->address) }}">
    @error('address')
    @enderror
    <label for="building">建物名</label>
    <input type="text" name="building" id="building" value="{{old('building',$user->building )}}">
    @error('building')
    @enderror
    <button type="submit">更新する</button>
</form>
@endsection