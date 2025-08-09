<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品詳細</title>
</head>
<body>
    <div class="img-container">
        <img src="{{ $item->image }}" alt="商品画像">
    </div>
    <div class="item-text-container">
        <h2 class="item-name">{{ $item->name }}</h2>
        <small class="item-brand">{{ $item->brand }}</small>
        <p class="item-price">{{ $item->price }}</p>
        <a href="{{ route('orders.create', ['item_id' => $item->id]) }}" class="item-purchase">購入手続きへ</a>
        <div class="item-explanation-container">
            <h3>商品説明</h3>
            <p class="item-explanation">{{ $item->description }}</p>
        </div>
        <div class="item-information">
            <h3>商品の情報</h3>
            <strong>カテゴリ</strong>
            <strong>商品の状態</strong>
            <span>{{ $item->condition}}</span>
        </div>
        <div class="item-comments-container">
            <h3>コメント</h3>
            <h4>商品へのコメント</h4>
            <textarea name="" id=""></textarea>
        </div>
    </div>
    
</body>
</html>
