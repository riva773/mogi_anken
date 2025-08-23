@extends('layouts.app')

@section('title')
<title>メール認証</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify.css') }}">
@endsection

@section('content')
<div class="verify">
    <p class="verify__text">登録していただいたメールアドレスに認証メールを送付しました。　メール認証を完了してください。</p>

    <a href="http://localhost:8025" target="_blank" rel="noopener" class="verify__button">認証はこちらから</a>

    <form action="{{ route('verification.send') }}" method="post" class="verify__form">
        @csrf
        <button type="submit" class="verify__resend">認証メールを再送する</button>
    </form>
</div>
@endsection