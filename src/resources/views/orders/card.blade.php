@extends('layouts.app')

@section('content')
<div class="container">
    <h2>カード支払い（表示のみ）</h2>
    <p>商品：{{ $item->name }}</p>
    <p>金額：¥{{ number_format($item->price) }}</p>

    <div id="payment-element" style="max-width: 480px;"></div>

</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe(@json($stripeKey));
    const elements = stripe.elements({
        clientSecret: @json($clientSecret)
    });

    const paymentElement = elements.create('payment');
    paymentElement.mount('#payment-element');
</script>
@endsection