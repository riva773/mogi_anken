<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録画面</title>
</head>
<body>
    <form action="" method="post">
        @csrf
        <h3>会員登録</h3>
        <p>ユーザー名</p>
        <input type="text" name="name" id="name">
        <p>メールアドレス</p>
        <input type="text" name="email" id="email">
        <p>パスワード</p>
        <input type="text" name="password" id="password">
        <p>確認用パスワード</p>
        <input type="text" name="password_confirmation" id="password_confirmation">
        <button type="submit" class="btn">登録する</button>
    </form>
    <a href="{{ route('login') }}">ログインはこちら</a>
</body>
</html>