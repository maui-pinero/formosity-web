<?php

use Braintree\Configuration;

function braintree_client_token() {
    Configuration::environment(config('services.braintree.environment'));
    Configuration::merchantId(config('services.braintree.merchant_id'));
    Configuration::publicKey(config('services.braintree.public_key'));
    Configuration::privateKey(config('services.braintree.private_key'));

    return \Braintree\ClientToken::generate();
}