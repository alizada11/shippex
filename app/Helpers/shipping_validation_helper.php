<?php
if (!function_exists('validate_shipping_record')) {
 function validate_shipping_record(array $record): array
 {
  $errors = [];

  // Normalize and trim key fields
  $serviceName  = isset($record['service_name'])  ? trim($record['service_name'])  : '';
  $providerName = isset($record['provider_name']) ? trim($record['provider_name']) : '';

  // At least one must be present
  if ($serviceName === '' && $providerName === '') {
   $errors[] = 'service_name_or_provider_name_required';
  }

  // Price validation
  if (isset($record['price']) && $record['price'] !== null) {
   if (!is_numeric($record['price'])) {
    $errors[] = 'price_must_be_numeric';
   } elseif ($record['price'] < 0) {
    $errors[] = 'price_must_be_positive';
   }
  }

  // Transit days validation
  if (isset($record['transit_days']) && $record['transit_days'] !== null) {
   if (!is_int($record['transit_days'])) {
    $errors[] = 'transit_days_must_be_int';
   } elseif ($record['transit_days'] < 0) {
    $errors[] = 'transit_days_must_be_positive';
   }
  }

  // Provider logo validation
  if (!empty($record['provider_logo'])) {
   if (!filter_var($record['provider_logo'], FILTER_VALIDATE_URL)) {
    $errors[] = 'provider_logo_must_be_valid_url';
   }
  }

  // Currency length
  if (!empty($record['currency']) && strlen($record['currency']) > 10) {
   $errors[] = 'currency_too_long';
  }

  return [empty($errors), $errors];
 }
}
