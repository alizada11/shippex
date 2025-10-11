<?php

namespace App\Services;

use Config\Services;

class EasyshipService
{
 protected $client;
 protected $token;

 public function __construct()
 {
  $this->client = Services::curlrequest();
  $this->token = getenv('EASYSHIP_API_TOKEN');
  if (!$this->token) {
   log_message('error', 'Easyship API token is not set.');
   throw new \Exception('Easyship API token is missing.');
  }
 }

 public function getRates(array $data)
 {

  $client = new \GuzzleHttp\Client();

  $response = $client->request('POST', 'https://public-api.easyship.com/2024-09/rates', [
   'headers' => [
    'Authorization' => "Bearer {$this->token}",
    'Accept' => 'application/json',
    'Content-Type' => 'application/json',
   ],
   'body' => json_encode([
    "origin_address" => [
     "line_1" => "Kennedy Town",
     "city" => "Hong Kong",
     "state" => "Yuen Long",
     "postal_code" => "0000",
     "country_alpha2" => "HK",
     "contact_name" => "Foo Bar",
     "contact_email" => "asd@asd.com"
    ],
    "destination_address" => [
     "line_1" => "5th Avenue",
     "city" => "New York",
     "state" => "NY",
     "postal_code" => "10001",
     "country_alpha2" => "US",
     "contact_name" => "John Doe",
     "contact_email" => "john@example.com"
    ],
    "incoterms" => "DDU",
    "insurance" => ["is_insured" => false],
    "courier_settings" => [
     "show_courier_logo_url" => false,
     "apply_shipping_rules" => true
    ],
    "shipping_settings" => [
     "units" => ["weight" => "kg", "dimensions" => "cm"]
    ],
    "parcels" => [
     [
      "total_actual_weight" => 1.2,
      "box" => [
       "length" => 10,
       "width" => 15,
       "height" => 5
      ],
      "items" => [
       [
        "quantity" => 1,
        "description" => "Book",
        "declared_currency" => "USD",
        "declared_customs_value" => 20,
        "hs_code" => "490199",   // <-- Books printed
        "origin_country_alpha2" => "HK"
       ]
      ]
     ]
    ]
   ])
  ]);

  dd($response->getBody());
 }
}
