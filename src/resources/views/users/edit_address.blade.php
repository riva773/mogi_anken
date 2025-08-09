<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>住所変更</title>
</head>

<body>
    <h2>住所の変更</h2>
    <form action="{{ route('address.update',['item_id' => $item->id]) }}" method="post">
        @csrf
        <h3>郵便番号</h3>
        <input type="text" name="postal_code" id="">
        <h3>住所</h3>
        <input type="text" name="address" id="">
        <h3>建物名</h3>
        <input type="text" name="building" id="">
        <button type="submit">更新する</button>
    </form>
</body>

</html>