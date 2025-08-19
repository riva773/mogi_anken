<div class="header">
    <img src="/images/logo.svg" alt="ロゴ">
    <form action="{{ route('items.index')}}" method="get">
        <input type="search" name="q" id="global-search" placeholder="なにをお探しですか？" value="{{ request('q') }}">
        <button type="submit"></button>
    </form>


    @auth
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">
            ログアウト
        </button>
    </form>
    @endauth

    @guest
    <a href="{{ route('login') }}">ログイン</a>
    @endguest

    <a href="{{route('mypage')}}">マイページ</a>
    <a href="{{ route('items.create') }}">出品</a>
</div>