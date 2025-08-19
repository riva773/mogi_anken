<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メール認証</title>
</head>

<body>
    @include('partials/simple-header')
    <p>
        登録していただいたメールアドレスに認証メールを送付しました。
        メール認証を完了してください。
    </p>
    <a href="https://mail.google.com/" target="_blank" rel="noopener">認証はこちらから</a>
    <form action="{{ route('verification.send') }}" method="post">
        @csrf
        <button type="submit">認証メールを再送する</button>
    </form>
</body>

</html>