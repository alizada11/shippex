<?php
if (!function_exists('validate_shipping_record')) {
 function validate_shipping_record(array $record): array
 {
  $errors = [];

  if (empty($record['service_name']) && empty($record['provider_name'])) {
   $errors[] = 'service_name_or_provider_name_required';
  }

  if (isset($record['price']) && $record['price'] !== null) {
   if (!is_numeric($record['price'])) $errors[] = 'price_must_be_numeric';
   else if ($record['price'] < 0) $errors[] = 'price_must_be_positive';
  }

  if (isset($record['transit_days']) && $record['transit_days'] !== null) {
   if (!is_int($record['transit_days'])) $errors[] = 'transit_days_must_be_int';
   else if ($record['transit_days'] < 0) $errors[] = 'transit_days_must_be_positive';
  }

  if (!empty($record['provider_logo'])) {
   if (!filter_var($record['provider_logo'], FILTER_VALIDATE_URL)) $errors[] = 'provider_logo_must_be_valid_url';
  }

  if (!empty($record['currency']) && strlen($record['currency']) > 10) {
   $errors[] = 'currency_too_long';
  }

  return [empty($errors), $errors];
 }
}
