@extends('layouts.app')

@section('content')
    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    <form action="{{ route('register') }}" method="post">
        @csrf
        <h3>会員登録</h3>
        <p>ユーザー名</p>
        <input type="text" name="name" id="name">
        <p>メールアドレス</p>
        <input type="email" name="email" id="email">
        <p>パスワード</p>
        <input type="password" name="password" id="password">
        <p>確認用パスワード</p>
        <input type="password" name="password_confirmation" id="password_confirmation">
        <button type="submit" class="btn">登録する</button>
    </form>
    <a href="{{ route('login') }}">ログインはこちら</a>
@endsection
