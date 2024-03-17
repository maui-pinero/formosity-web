@extends('layouts.app')

@section('body_content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Make a Payment</div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="post" action="{{ route('payment.create') }}">
                            @csrf

                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="text" class="form-control" id="amount" name="amount" value="100.00">
                            </div>

                            <div class="form-group">
                                <label for="nonce"></label>
                                <div id="dropin-container"></div>
                                <input type="hidden" id="nonce" name="payment_method_nonce">
                            </div>

                            <button type="submit" class="btn btn-primary">Submit Payment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://js.braintreegateway.com/web/dropin/1.35.0/js/dropin.min.js"></script>
    <script>
        var form = document.querySelector('form');
        var client_token = "{{ braintree_client_token() }}";

        braintree.dropin.create({
            authorization: client_token,
            container: '#dropin-container'
        }, function (createErr, instance) {
            if (createErr) {
                console.error(createErr);
                return;
            }

            form.addEventListener('submit', function (event) {
                event.preventDefault();

                instance.requestPaymentMethod(function (err, payload) {
                    if (err) {
                        console.error(err);
                        return;
                    }

                    document.querySelector('#nonce').value = payload.nonce;
                    form.submit();
                });
            });
        });
    </script>
@endsection