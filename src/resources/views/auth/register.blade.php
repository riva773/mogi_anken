@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('title')
<title>会員登録</title>
@endsection

@section('content')
<div class="auth">
    <div class="auth__card">
        <h1 class="auth__title">会員登録</h1>

        @if($errors->any())
        <ul class="form-errors">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif

        <form action="{{ route('register') }}" method="post" class="auth__form">
            @csrf
            <div class="form-group">
                <label for="name">ユーザー名</label>
                <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" class="form-input">
            </div>
            <div class="form-group">
                <label for="password_confirmation">確認用パスワード</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input">
            </div>
            <button type="submit" class="btn-submit">登録する</button>
        </form>

        <a href="{{ route('login') }}" class="helper-link">ログインはこちら</a>
    </div>
</div>
@endsection