<?php

namespace Config;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

class Paypal
{
 public $clientId = 'AR_PoU6NaXw2h4y9qwFGoYMBMpw9_I0AzvNGSARRucV84VoZA_x1OHH9781pe1E4rdiW7uvr7st4lX4j';
 public $clientSecret = 'EDc4Vk5vARpM_CXSYFboXgcejBDkdimSS-fu_HcJwnfJA4ruayFSW2HRkjS0uExFi_8lk2-vbMG8kbKK';
 public $environment;
 public $client;

 public function __construct()
 {
  $this->environment = new SandboxEnvironment($this->clientId, $this->clientSecret);
  $this->client = new PayPalHttpClient($this->environment);
 }
}
