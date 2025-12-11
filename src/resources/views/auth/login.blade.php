@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('title')
<title>ログイン</title>
@endsection

@section('content')
<div class="auth">
    <div class="auth__card">
        <h1 class="auth__title">ログイン</h1>
        @foreach($errors->all() as $error)
            <li class="error">{{ $error }}</li>
        @endforeach
        <form action="{{ route('login') }}" method="post" class="auth__form">
            @csrf
            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" class="form-input">
            </div>
            <button type="submit" class="btn-submit">ログインする</button>
        </form>
        <a href="{{ route('register') }}" class="helper-link">会員登録はこちら</a>
    </div>
</div>
@endsection