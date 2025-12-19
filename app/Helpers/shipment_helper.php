<?php

if (!function_exists('getCourierLogoUrl')) {
 /**
  * Get the logo URL for the given retailer name.
  *
  * @param string $retailer The name of the courier retailer.
  * @return string The logo URL.
  */
 function getCourierLogoUrl($retailer)
 {
  // Map of courier names to logo files
  $courierLogos = [
   'DHL' => 'dhl.svg',
   'UPS' => 'ups.svg',
   'Aramex' => 'aramex.svg',
   'FlatExportRate' => 'flatexportrate.svg',
   'SFExpress' => 'sf-express.svg',
   'Asendia' => 'asendia.svg',
   'Passport' => 'passport.svg',
   'FedEx' => 'fedex.svg',
   'USPS' => 'usps.svg',
   'Sendle' => 'sendle.svg',
   'Purolator' => 'purolator.svg',
   'Canada Post' => 'canada-post.svg',
   'Canpar' => 'canpar.svg',
   'StarTrack' => 'star-track.png',
   'CouriersPlease' => 'couriers-please.svg',
   'AlliedExpress' => 'alliedexpress.svg',
   'TNT' => 'tnt.svg',
   'Quantium' => 'quantium.svg',
   'Toll' => 'toll.svg',
   'HKPost' => 'hong-kong-post.svg',
   'APG' => 'apg.svg',
   'Hubbed' => 'hubbed.svg',
  ];

  // Check if the courier has a logo, if not return a default logo or empty string
  if (array_key_exists($retailer, $courierLogos)) {
   return base_url("logos/{$courierLogos[$retailer]}");
  }

  // Return a default logo or empty string if no logo is found
  return null; // Adjust the default logo file as needed
 }
}




if (!function_exists('calculate_shipment_progress')) {
 /**
  * Calculate progress percentage based on status
  */
 function calculate_shipment_progress($status)
 {
  $progress_map = [
   'pending'   => 10,
   'accepted'  => 25,
   'shipping'  => 65,
   'shipped'   => 85,
   'delivered' => 100,
   'canceled'  => 0
  ];

  return $progress_map[strtolower($status)] ?? 10;
 }
}

if (!function_exists('get_progress_message')) {
 /**
  * Get progress message based on status
  */
 function get_progress_message($status)
 {
  $messages = [
   'pending'   => 'Order received - Processing will begin shortly',
   'accepted'  => 'Order accepted - Preparing for shipment',
   'shipping'  => 'Package is in transit to destination facility',
   'shipped'   => 'Package has departed from facility',
   'delivered' => 'Package has been delivered successfully',
   'canceled'  => 'Order has been canceled'
  ];

  return $messages[strtolower($status)] ?? 'Order processing started';
 }
}

if (!function_exists('get_progress_color')) {
 /**
  * Get Bootstrap progress bar color based on status
  */
 function get_progress_color($status)
 {
  $colors = [
   'pending'   => 'bg-info',
   'accepted'  => 'bg-primary',
   'shipping'  => 'bg-warning',
   'shipped'   => 'bg-info',
   'delivered' => 'bg-success',
   'canceled'  => 'bg-danger'
  ];

  return $colors[strtolower($status)] ?? 'bg-info';
 }
}

if (!function_exists('calculate_estimated_delivery')) {
 /**
  * Calculate estimated delivery date based on status and creation date
  */
 function calculate_estimated_delivery($status, $created_at, $destination = null)
 {
  $created_date = is_string($created_at) ? strtotime($created_at) : $created_at;

  // Base delivery estimates (in days)
  $delivery_times = [
   'pending'   => 7,  // Standard delivery time
   'accepted'  => 6,  // Slightly reduced
   'shipping'  => 3,  // Getting closer
   'shipped'   => 2,  // Almost there
   'delivered' => 0,  // Already delivered
   'canceled'  => 0   // No delivery
  ];

  $days = $delivery_times[strtolower($status)] ?? 5;

  // Adjust based on destination (you can customize this)
  if ($destination) {
   $international_destinations = ['US', 'CA', 'UK', 'AU', 'DE', 'FR'];
   if (in_array(strtoupper($destination), $international_destinations)) {
    $days += 3; // Add extra days for international
   }
  }

  $delivery_date = strtotime("+{$days} days", $created_date);

  return date('M j, Y', $delivery_date) . ' by 5:00 PM';
 }
}

if (!function_exists('get_shipment_timeline')) {
 /**
  * Get complete timeline for shipment tracking
  */
 function get_shipment_timeline($status, $created_at)
 {
  $timeline = [
   'pending' => [
    'icon' => 'fas fa-clipboard-list',
    'title' => 'Order Received',
    'description' => 'We have received your order',
    'active' => true,
    'completed' => true
   ],
   'accepted' => [
    'icon' => 'fas fa-check-circle',
    'title' => 'Order Accepted',
    'description' => 'Your order has been processed',
    'active' => in_array($status, ['accepted', 'shipping', 'shipped', 'delivered']),
    'completed' => in_array($status, ['accepted', 'shipping', 'shipped', 'delivered'])
   ],
   'shipping' => [
    'icon' => 'fas fa-shipping-fast',
    'title' => 'In Transit',
    'description' => 'Package is on the way',
    'active' => in_array($status, ['shipping', 'shipped', 'delivered']),
    'completed' => in_array($status, ['shipping', 'shipped', 'delivered'])
   ],
   'shipped' => [
    'icon' => 'fas fa-truck',
    'title' => 'Out for Delivery',
    'description' => 'Package is in your area',
    'active' => in_array($status, ['shipped', 'delivered']),
    'completed' => in_array($status, ['shipped', 'delivered'])
   ],
   'delivered' => [
    'icon' => 'fas fa-box-open',
    'title' => 'Delivered',
    'description' => 'Package has been delivered',
    'active' => $status === 'delivered',
    'completed' => $status === 'delivered'
   ]
  ];

  return $timeline;
 }
}
