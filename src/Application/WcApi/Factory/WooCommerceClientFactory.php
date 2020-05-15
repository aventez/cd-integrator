<?php

namespace App\Application\WcApi\Factory;

use App\Application\WcApi\Client;

class WooCommerceClientFactory
{
    public function create(): Client
    {
        // TODO: WYRZUCIĆ TO PO TEŚCIE DO ENV, PILNIE
        $client = new Client(
            'https://kawiarskiegadzety.pl',
            'ck_f05a89b3b281f64f59951261b2fdba8fe7dac306',
            'cs_99d33288c5c8334841a3bbc55095a0f8eba4db3a',
            [
                'wp_api' => true,
                'version' => 'wc/v3',
                'query_string_auth' => true
            ]
        );

        return $client;
    }
}