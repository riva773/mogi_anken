<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品購入画面</title>
</head>

<body>
    <div class="items-container">
        <img src="{{ $item->image }}" alt="商品画像">
        <h2>{{ $item->name }}</h2>
        <p>{{ $item->price }}</p>
    </div>
    <div class="howto-order">
        <p>支払い方法</p>
        <select name="payment_method">
            <option value="コンビニ支払い">コンビニ支払い</option>
            <option value="カード支払い">カード支払い</option>
        </select>
    </div>
    <div class="order-address">
        <p>配送先</p>
        <a href="{{ route('address.edit', ['item_id' => $item->id]) }}">変更する</a>
        <p>〒 {{ $user->postal_code }}</p>
        <p>{{ $user->address }}</p>
        <p>{{ $user->building }}</p>
    </div>

    <div class="order-container">
        <p>商品代金</p>
        <p>{{ $item->price }}</p>
        <p>支払い方法</p>
        <p>コンビニ払い</p>
        <button>購入する</button>
    </div>
</body>

</html>