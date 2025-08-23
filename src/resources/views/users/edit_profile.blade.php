@extends('layouts.app')

@section('title')
<title>プロフィール編集</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile">
    @include('partials.errors')
    <h2 class="profile__title">プロフィール設定</h2>

    <form action="{{ route('mypage.profile.update') }}" method="post" enctype="multipart/form-data" class="profile__form">
        @csrf
        @method('PUT')

        <div class="profile__avatar-row">
            <div class="avatar-preview">
                @if(!empty($user->avatar_url))
                <img src="{{ $user->avatar_url }}" alt="avatar">
                @endif
            </div>
            <input type="file" name="avatar" id="avatar" class="visually-hidden">
            <label for="avatar" class="btn-select">画像を選択する</label>
        </div>

        <div class="form-group">
            <label for="name" class="label">ユーザー名</label>
            <input type="text" name="name" id="name" class="input" value="{{ old('name',$user->name) }}">
        </div>

        <div class="form-group">
            <label for="postal_code" class="label">郵便番号</label>
            <input type="text" name="postal_code" id="postal_code" class="input" value="{{ old('postal_code',$user->postal_code) }}">
        </div>

        <div class="form-group">
            <label for="address" class="label">住所</label>
            <input type="text" name="address" id="address" class="input" value="{{ old('address',$user->address) }}">
        </div>

        <div class="form-group">
            <label for="building" class="label">建物名</label>
            <input type="text" name="building" id="building" class="input" value="{{ old('building',$user->building) }}">
        </div>

        <div class="action-bar">
            <button type="submit" class="btn-submit">更新する</button>
        </div>
    </form>
</div>
@endsection