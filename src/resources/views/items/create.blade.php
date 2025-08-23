@extends('layouts.app')
@section('title')
<title>商品出品</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
@include('partials.errors')
<form action="{{ route('items.store') }}" method="post" enctype="multipart/form-data" class="sell-form">
    @csrf
    <section class="section">
        <h2 class="title">商品の出品</h2>
        <label for="image" class="form-label">商品画像</label>
        <div class="image-uploader">
            <input id="image" type="file" name="image" accept="image/*" class="file-input">
            <span class="file-input-label">画像を選択する</span>
        </div>
        @error('image')
        @enderror
    </section>

    <section class="section">
        <h3 class="subtitle with-divider">商品の詳細</h3>

        <div class="categories">
            <p class="form-label">カテゴリー</p>
            @php
            $categories = [
            'ファッション','家電','インテリア','レディース','メンズ','コスメ',
            '本','ゲーム','スポーツ','キッチン','ハンドメイド','アクセサリー',
            'おもちゃ','ベビー・キッズ',
            ];
            $selected = collect(old('categories', []))->all();
            @endphp
            <div class="chips">
                @foreach($categories as $cat)
                <label class="chip">
                    <input type="checkbox" name="categories[]" value="{{ $cat }}" @checked(in_array($cat, $selected)) class="radio">
                    <span class="chip-text">{{ $cat }}</span>
                </label>
                @endforeach
            </div>
            @error('categories')
            @enderror
        </div>

        <div class="condition">
            <p class="form-label">商品の状態</p>
            <div class="condition-box">
                <select name="condition" id="conditionSelect" class="select">
                    <option value="" id="conditionPlaceholder" {{ old('condition') ? '' : 'selected' }} disabled>選択してください</option>
                    <option value="良好" @selected(old('condition')==='良好' )>良好</option>
                    <option value="目立った傷や汚れなし" @selected(old('condition')==='目立った傷や汚れなし' )>目立った傷や汚れなし</option>
                    <option value="やや傷や汚れあり" @selected(old('condition')==='やや傷や汚れあり' )>やや傷や汚れあり</option>
                    <option value="状態が悪い" @selected(old('condition')==='状態が悪い' )>状態が悪い</option>
                </select>
            </div>
            @error('condition')
            @enderror
        </div>
    </section>

    <section class="section mt-20">
        <h3 class="subtitle with-divider">商品名と説明</h3>

        <div class="field">
            <label for="name" class="form-label">商品名</label><br>
            <input id="name" type="text" name="name" value="{{ old('name') }}" class="input">
            @error('name')
            @enderror
        </div>

        <div class="field">
            <label for="brand" class="form-label">ブランド名</label><br>
            <input id="brand" type="text" name="brand" value="{{ old('brand') }}" class="input">
        </div>

        <div class="field">
            <label for="description" class="form-label">商品の説明</label><br>
            <textarea id="description" name="description" rows="6" class="textarea">{{ old('description') }}</textarea>
            @error('description')
            @enderror
        </div>

        <div class="field">
            <label for="price" class="form-label">販売価格</label><br>
            <input id="price" type="number" name="price" value="{{ old('price') }}" min="0" step="1" placeholder="¥" class="input">
            @error('price')
            @enderror
        </div>
    </section>

    <div class="action-bar">
        <button type="submit" class="btn-submit">出品する</button>
    </div>
</form>

<script>
    (function() {
        const sel = document.getElementById('conditionSelect');
        const ph = document.getElementById('conditionPlaceholder');

        if (!sel || !ph) return;

        const hidePlaceholder = () => {
            ph.hidden = true;
        };
        const showIfEmpty = () => {
            if (!sel.value) ph.hidden = false;
        };

        if (sel.value) {
            ph.hidden = true;
        } else {
            ph.hidden = false;
        }

        sel.addEventListener('mousedown', hidePlaceholder);
        sel.addEventListener('focus', hidePlaceholder);
        sel.addEventListener('keydown', hidePlaceholder);
        sel.addEventListener('click', hidePlaceholder);
        sel.addEventListener('change', hidePlaceholder);
        sel.addEventListener('blur', showIfEmpty);
    })();
</script>
@endsection