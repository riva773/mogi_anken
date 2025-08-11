<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ヘッダー</title>
</head>

<body>
    <div class="header">
        <img src="/images/logo.svg" alt="ロゴ">
        <input type="search" name="" id="" placeholder="なにをお探しですか？">
    @auth
        <form action="{{ route('logout') }}" method="POST">
        @csrf
            <button type="submit">
                ログアウト
            </button>
        </form>
    @endauth
        <a href="#">マイページ</a>
        <a href="">出品</a>
    </div>
</body>

</html>