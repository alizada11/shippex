<?php
if (!function_exists('get_standard_carriers')) {
 function get_standard_carriers(): array
 {
  return [
   'FedEx',
   'DHL',
   'UPS',
   'USPS',
   'DPD',
   'Royal Mail',
   'China Post',
   'Cainiao',
   'Aramex',
   'HK Post',
   'EMS',
   'TNT',
   'GLS',
   'YunExpress',
   'S.F. Express',
   'SF Express',
   'PostNL',
   'La Poste',
   'Correos',
   'Japan Post',
   'Australia Post',
   'Canada Post',
   'Hermes',
   'SEUR',
   'Delhivery',
   'Blue Dart',
   'NZ Post',
   'Korean Post',
   'Korea Post'
  ];
 }
}

if (!function_exists('normalize_text')) {
 function normalize_text(string $s): string
 {
  return trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($s))));
 }
}

if (!function_exists('detect_provider_from_text')) {
 function detect_provider_from_text(string $text): ?string
 {
  $carriers = array_map('strtolower', get_standard_carriers());
  $textLower = strtolower($text);
  usort($carriers, function ($a, $b) {
   return strlen($b) - strlen($a);
  });
  foreach ($carriers as $c) {
   if ($c === '') continue;
   if (strpos($textLower, strtolower($c)) !== false) {
    foreach (get_standard_carriers() as $orig) {
     if (strtolower($orig) === $c) return $orig;
    }
    return ucfirst($c);
   }
  }
  $parts = preg_split('/\s+/', trim($text));
  return $parts[0] ?? null;
 }
}

if (!function_exists('parse_shipping_services_html')) {
 function parse_shipping_services_html(string $html, int $id): array
 {
  libxml_use_internal_errors(true);
  $doc = new \DOMDocument();
  $doc->loadHTML('<?xml encoding="utf-8" ?>' . $html);
  $xpath = new \DOMXPath($doc);

  $records = [];
  $nodes = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' pricing-service ')]");

  foreach ($nodes as $node) {
   $imgNode = $xpath->query('.//img', $node)->item(0);
   $provider_logo = $imgNode ? trim($imgNode->getAttribute('src')) : null;

   $serviceNameNode = $xpath->query(".//*[contains(concat(' ', normalize-space(@class), ' '), ' service-name ')]", $node)->item(0);
   $service_name = $serviceNameNode ? normalize_text($serviceNameNode->textContent) : null;

   $logoArea = $xpath->query(".//*[contains(concat(' ', normalize-space(@class), ' '), ' service-logo ')]", $node)->item(0);
   $provider_name = null;
   if ($logoArea) {
    $logoText = normalize_text($logoArea->textContent);
    if ($logoText) $provider_name = detect_provider_from_text($logoText);
   }
   if (!$provider_name && $service_name) {
    $provider_name = detect_provider_from_text($service_name);
   }

   $priceNode = $xpath->query(".//*[contains(concat(' ', normalize-space(@class), ' '), ' service-price ')]", $node)->item(0);
   $price_raw = $priceNode ? normalize_text($priceNode->textContent) : null;
   $currency = null;
   $price = null;
   if ($price_raw) {
    if (preg_match('/([€$£¥₹]|[A-Z]{3})/u', $price_raw, $m)) $currency = $m[1];
    $num = preg_replace('/[^\d\.,-]/', '', $price_raw);
    $num = str_replace(' ', '', $num);
    if (substr_count($num, ',') > 0 && substr_count($num, '.') === 0) {
     $num = str_replace(',', '.', $num);
    } else {
     $num = str_replace(',', '', $num);
    }
    $price = is_numeric($num) ? (float)$num : null;
   }

   $ratingNode = $xpath->query(".//*[contains(concat(' ', normalize-space(@class), ' '), ' service-info-rating ')]", $node)->item(0);
   $rating = null;
   if ($ratingNode) {
    $svgCount = $xpath->query('.//svg', $ratingNode)->length;
    if ($svgCount > 0) $rating = (int)$svgCount;
    else {
     $rtext = normalize_text($ratingNode->textContent);
     if (preg_match('/([0-9]+(\.[0-9]+)?)/', $rtext, $m)) $rating = (float)$m[1];
    }
   }

   $transitNode = $xpath->query(".//*[contains(concat(' ', normalize-space(@class), ' '), ' service-info-transit-time ')]", $node)->item(0);
   $transit_text = null;
   $transit_days = null;
   if ($transitNode) {
    $transit_text = normalize_text($transitNode->textContent);
    if (preg_match('/(\d+)\s*(Business|business|Business\s+days|days|day)/i', $transit_text, $m)) $transit_days = (int)$m[1];
   }

   $featuresNode = $xpath->query(".//*[contains(concat(' ', normalize-space(@class), ' '), ' service-details ')]", $node)->item(0);
   $features = [];

   if ($featuresNode) {
    foreach ($featuresNode->childNodes as $child) {
     // Skip empty text nodes
     $text = trim($child->textContent);
     if ($text === '') continue;

     // If this is a <br> or similar separator, skip
     if ($child->nodeName === 'br') continue;

     // Some lines may be concatenated due to text nodes; split by colon
     if (strpos($text, ':') !== false) {
      [$k, $v] = array_map('trim', explode(':', $text, 2));
      $key = strtolower(preg_replace('/[^a-z0-9_]+/i', '_', $k));
      $features[$key] = $v;
     } else {
      $features[] = $text;
     }
    }
   }

   $quoteKeyNode = $xpath->query(".//*[@data-quote-key]/@data-quote-key", $node)->item(0);
   $quote_key = $quoteKeyNode ? trim($quoteKeyNode->textContent) : null;

   $record = [
    'provider_name' => $provider_name,
    'service_name' => $service_name,
    'provider_logo' => $provider_logo,
    'price' => $price,
    'currency' => $currency,
    'rating' => $rating,
    'transit_text' => $transit_text,
    'transit_days' => $transit_days,
    'features' => $features,
    'quote_key' => $quote_key,
    'request_id' => $id,
   ];

   if ($record['service_name'] || $record['provider_logo'] || $record['price'] !== null) {
    $records[] = $record;
   }
  }

  libxml_clear_errors();
  return $records;
 }
}
