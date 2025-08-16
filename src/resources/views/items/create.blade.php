@extends('layouts.app')
@section('title')
<title>商品出品</title>
@endsection

@section('content')
<form action="{{ route('items.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <section>
        <h2>商品の出品</h2>
        <label for="image">商品画像</label><br>
        <input id="image" type="file" name="image" accept="image/*">
    </section>

    <section>
        <h3>商品の詳細</h3>
        <div>
            <p>カテゴリー</p>
            @php
            $categories = [
            'ファッション','家電','インテリア','レディース','メンズ','コスメ',
            '本','ゲーム','スポーツ','キッチン','ハンドメイド','アクセサリー',
            'おもちゃ','ベビー・キッズ',
            ];
            $selected = old('category');
            @endphp
            <div>
                @foreach($categories as $cat)
                <label>
                    <input type="radio" name="category" value="{{ $cat }}" @checked($selected===$cat)>
                    <span>{{ $cat }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- 商品の状態 --}}
        <div>
            <p>商品の状態</p>
            <select name="condition">
                <option value="">選択してください</option>
                <option value="良好" @selected(old('condition')==='良好' )>良好</option>
                <option value="目立った傷や汚れなし" @selected(old('condition')==='目立った傷や汚れなし' )>目立った傷や汚れなし</option>
                <option value="やや傷や汚れあり" @selected(old('condition')==='やや傷や汚れあり' )>やや傷や汚れあり</option>
                <option value="状態が悪い" @selected(old('condition')==='状態が悪い' )>状態が悪い</option>
            </select>

        </div>
    </section>

    <section style="margin-top:20px;">
        <h3>商品名と説明</h3>

        <div style="margin-top:8px;">
            <label for="name">商品名</label><br>
            <input id="name" type="text" name="name" value="{{ old('name') }}">
        </div>

        <div>
            <label for="brand">ブランド名</label><br>
            <input id="brand" type="text" name="brand" value="{{ old('brand') }}">
        </div>

        <div>
            <label for="description">商品の説明</label><br>
            <textarea id="description" name="description" rows="6">{{ old('description') }}</textarea>
        </div>

        <div>
            <label for="price">販売価格</label><br>
            <input id="price" type="number" name="price" value="{{ old('price') }}" min="1" step="1" placeholder="¥">
        </div>
    </section>

    <div>
        <button type="submit">出品する</button>
    </div>
</form>

@endsection