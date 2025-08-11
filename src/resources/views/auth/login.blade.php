@extends('layouts.app')

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログインページ</title>
</head>
<body>
@section('content')
    <form action="{{ route('login') }}" method="post">
        @csrf
        <h3>ログイン</h3>
        <p>メールアドレス</p>
        <input type="text" name="email" id="email">
        <p>パスワード</p>
        <input type="password" name="password" id="password">
        <button type="submit" class= "btn">ログインする</button>
    </form>
    <a href="{{ route('register') }}">会員登録はこちら</a>
@endsection
</body>
</html>