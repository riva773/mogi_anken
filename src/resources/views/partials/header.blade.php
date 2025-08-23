@php
$isSimple = request()->routeIs([
'register',
'login',
'verification.notice'
]);
@endphp

@if($isSimple)
<header class="g-header">
    <div class="g-header__inner">
        <div class="g-header__logo">
            <a href="{{ route('items.index') }}">
                <img src="/images/logo.svg" alt="ロゴ">
            </a>
        </div>
    </div>
</header>
@else
<header class="g-header">
    <div class="g-header__inner">
        <div class="g-header__logo">
            <a href="{{ route('items.index') }}">
                <img src="/images/logo.svg" alt="ロゴ">
            </a>
        </div>

        <form class="g-header__search" action="{{ route('items.index')}}" method="get">
            <input
                type="search"
                name="q"
                id="global-search"
                placeholder="なにをお探しですか？"
                value="{{ request('q') }}">
        </form>

        <div class="g-header__spacer"></div>

        <nav class="g-header__nav">
            @auth
            <form action="{{ route('logout') }}" method="POST" class="g-header__nav-item">
                @csrf
                <button type="submit">ログアウト</button>
            </form>
            @endauth

            @guest
            <a class="g-header__nav-item" href="{{ route('login') }}">ログイン</a>
            @endguest

            <a class="g-header__nav-item" href="{{ route('mypage') }}">マイページ</a>
            <a class="g-header__nav-item g-header__nav-item--primary" href="{{ route('items.create') }}">出品</a>
        </nav>
    </div>
</header>
@endif