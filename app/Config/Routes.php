<?php

use App\Controllers\Shipping;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
/* *****************************************************************
*                      Public Access                               *
*******************************************************************/
$routes->get('/', 'Home::index');
$routes->get('/warehouses', 'Home::warehouses');
$routes->get('warehouses/(:segment)', 'WarehouseController::view/$1');

// Shipping
$routes->get('shipping/form', 'Shipping::form');
$routes->get('shipping/notify_user/(:num)', 'Shipping::notify_user/$1');
$routes->post('shipping/getRates', 'Shipping::getRates');
$routes->post('shipping/book', 'Shipping::book');
$routes->get('shipping/rates', 'Shipping::shippingRates');
$routes->post('shipping/updateInvoice/(:num)', 'Shipping::updateInvoice/$1');
$routes->post('shipping/createOrder/(:num)', 'Shipping::createOrder/$1');
$routes->post('shipping/captureOrder/(:num)', 'Shipping::captureOrder/$1');
$routes->post('shopper/createOrder/(:num)', 'Shopper::createOrder/$1');
$routes->post('shopper/captureOrder/(:num)', 'Shopper::captureOrder/$1');

// services
$routes->get('/services', 'Services::index');

if (file_exists(APPPATH . 'Modules/Blog/Config/Routes.php')) {
 require APPPATH . 'Modules/Blog/Config/Routes.php';
}

// Auth
$routes->get('/login', 'Auth::login', ['filter' => 'noauth']);
$routes->post('/login-post', 'Auth::loginPost', ['filter' => 'noauth']);
$routes->get('/logout', 'Auth::logout');
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::registerPost');
$routes->get('/forgot', 'Auth::forgot');
$routes->post('/forgot', 'Auth::forgotPost');
$routes->get('/reset-password/(:any)', 'Auth::reset/$1');
$routes->post('/reset-password/(:any)', 'Auth::resetPost/$1');
$routes->get('/change-password', 'Auth::changePassword');
$routes->post('/change-password', 'Auth::changePasswordPost');
/* *****************************************************************
*                      admin routes                                *
*******************************************************************/
$routes->get('/dashboard', 'Admin::index',  ['filter' => 'authGuard:admin']);

// warehouses
$routes->group(
 '',
 ['filter' => 'role:admin', 'namespace' => 'App\Controllers'],
 function ($routes) {
  // warehouse
  $routes->get('/warehouse', 'WarehouseController::index');
  $routes->get('/warehouse/create', 'WarehouseController::create');
  $routes->post('/warehouse/store', 'WarehouseController::store');
  $routes->get('/warehouse/edit/(:num)', 'WarehouseController::edit/$1');
  $routes->post('/warehouse/update/(:num)', 'WarehouseController::update/$1');
  $routes->get('/warehouse/delete/(:num)', 'WarehouseController::delete/$1');

  // Shipping reques
  $routes->get('/shipping/requests', 'Shipping::requests');
  $routes->get('/shipping/details/(:num)', 'Shipping::details/$1');
  $routes->get('/shipping/delete/(:num)', 'Shipping::destroy/$1');
  $routes->post('/shipping/update-status/(:num)', 'Shipping::updateStatus/$1');

  // admin shopper routes
  $routes->get('admin/shopper/requests', 'Admin::shopperRequests');
  $routes->get('admin/shopper/requests/edit/(:num)', 'Admin::edit/$1');
  $routes->get('admin/shopper/requests/view/(:num)', 'Admin::shopperView/$1');
  $routes->post('admin/shopper/requests/set_price', 'Admin::set_price');
 }
);
// faqs
$routes->get('faqs', 'Admin\FaqController::faqs');
$routes->get('customers/faqs', 'CustomersController::faqs');
$routes->group('admin/faqs', ['filter' => 'role:admin', 'namespace' => 'App\Controllers\Admin'], function ($routes) {
 $routes->get('/', 'FaqController::index');
 $routes->get('create', 'FaqController::create');
 $routes->post('store', 'FaqController::store');
 $routes->get('edit/(:num)', 'FaqController::edit/$1');
 $routes->post('update/(:num)', 'FaqController::update/$1');
 $routes->get('delete/(:num)', 'FaqController::delete/$1');
});
$routes->group(
 'admin',
 ['filter' => 'role:admin', 'namespace' => 'App\Controllers'],
 function ($routes) {
  $routes->get('fonts/select', 'FontsController::select', ['as' => 'font_select']);
  $routes->post('fonts/save', 'FontsController::save', ['as' => 'font_save']);
  $routes->get('fonts/make_default/(:num)', 'FontsController::make_default/$1');
  // Hero section of home page
  $routes->get('cms/hero-section/edit', 'Admin\Home::edit');
  $routes->post('cms/hero-section/update', 'Admin\Home::update');

  // how it works section
  $routes->get('cms/how-it-works', 'Admin\HowItWorksController::index');
  $routes->get('cms/how-it-works/create', 'Admin\HowItWorksController::create');
  $routes->post('cms/how-it-works/store', 'Admin\HowItWorksController::store');
  $routes->get('cms/how-it-works/edit/(:num)', 'Admin\HowItWorksController::edit/$1');
  $routes->post('cms/how-it-works/update/(:num)', 'Admin\HowItWorksController::update/$1');
  $routes->get('cms/how-it-works/delete/(:num)', 'Admin\HowItWorksController::delete/$1');
  // home locations
  $routes->get('cms/locations', 'Admin\LocationController::index');
  $routes->get('cms/locations/create', 'Admin\LocationController::create');
  $routes->post('cms/locations/store', 'Admin\LocationController::store');
  $routes->get('cms/locations/edit/(:num)', 'Admin\LocationController::edit/$1');
  $routes->post('cms/locations/update/(:num)', 'Admin\LocationController::update/$1');
  $routes->get('cms/locations/delete/(:num)', 'Admin\LocationController::delete/$1');
  // Deliverred today
  $routes->get('cms/delivered-today', 'Admin\DeliveredToday::index');
  $routes->get('cms/delivered-today/create', 'Admin\DeliveredToday::create');
  $routes->post('cms/delivered-today/store', 'Admin\DeliveredToday::store');
  $routes->get('cms/delivered-today/edit/(:num)', 'Admin\DeliveredToday::edit/$1');
  $routes->post('cms/delivered-today/update/(:num)', 'Admin\DeliveredToday::update/$1');
  $routes->get('cms/delivered-today/delete/(:num)', 'Admin\DeliveredToday::delete/$1');

  // promotions
  $routes->get('cms/promo-cards', 'Admin\PromoCards::index');
  $routes->get('cms/promo-cards/create', 'Admin\PromoCards::create');
  $routes->post('cms/promo-cards/store', 'Admin\PromoCards::store');
  $routes->get('cms/promo-cards/edit/(:num)', 'Admin\PromoCards::edit/$1');
  $routes->post('cms/promo-cards/update/(:num)', 'Admin\PromoCards::update/$1');
  $routes->get('cms/promo-cards/delete/(:num)', 'Admin\PromoCards::delete/$1');
  // how it works

  $routes->get('how-it-works', 'HowItWorks::admin_index');
  $routes->get('how-it-works/create', 'HowItWorks::create');
  $routes->post('how-it-works/store', 'HowItWorks::store');
  $routes->get('how-it-works/edit/(:num)', 'HowItWorks::edit/$1');
  $routes->post('how-it-works/update/(:num)', 'HowItWorks::update/$1');
  $routes->get('how-it-works/delete/(:num)', 'HowItWorks::delete/$1');

  // Steps routes
  $routes->get('cms/steps', 'Admin\Steps::index');
  $routes->get('cms/steps/create', 'Admin\Steps::create');
  $routes->post('cms/steps/create', 'Admin\Steps::store');
  $routes->get('cms/steps/edit/(:num)', 'Admin\Steps::edit/$1');
  $routes->post('cms/steps/edit/(:num)', 'Admin\Steps::update/$1');
  $routes->get('cms/steps/delete/(:num)', 'Admin\Steps::delete/$1');

  // Why Choose Us routes
  $routes->get('cms/why-choose', 'Admin\WhyChoose::index');
  $routes->get('cms/why-choose/create', 'Admin\WhyChoose::create');
  $routes->post('cms/why-choose/create', 'Admin\WhyChoose::store');
  $routes->get('cms/why-choose/edit/(:num)', 'Admin\WhyChoose::edit/$1');
  $routes->post('cms/why-choose/edit/(:num)', 'Admin\WhyChoose::update/$1');
  $routes->get('cms/why-choose/delete/(:num)', 'Admin\WhyChoose::delete/$1');
 }

);
$routes->get('/how-it-works', 'HowItWorks::index');


/* *****************************************************************
*                      customer routes                             *
*******************************************************************/
$routes->get('/customer/dashboard', 'CustomersController::dashboard', ['filter' => 'authGuard:customer']);


$routes->group(
 'customer',
 ['filter' => 'role:customer', 'namespace' => 'App\Controllers'],
 function ($routes) {
  $routes->get('shipping/requests', 'CustomersController::requests');
  $routes->get('shipping/details/(:num)', 'CustomersController::details/$1');
  $routes->get('shipping/delete/(:num)', 'CustomersController::destroy/$1');
 }
);
$routes->group(
 'shopper',
 ['filter' => 'role:customer', 'namespace' => 'App\Controllers'],
 function ($routes) {
  $routes->get('/', 'Shopper::index');
  $routes->post('submit', 'Shopper::submit');
  $routes->get('thank-you', 'Shopper::thankYou');
  $routes->get('requests', 'Shopper::myRequests');
  $routes->get('requests/edit/(:num)', 'Shopper::edit/$1');
  $routes->get('requests/view/(:num)', 'Shopper::view/$1');
  $routes->post('requests/update/(:num)', 'Shopper::update/$1');
  $routes->get('/shopper/view/(:num)', 'Shopper::view/$1');
 }
);

$routes->group(
 'profile',
 ['filter' => 'role:customer', 'namespace' => 'App\Controllers'],
 function ($routes) {
  $routes->get('/', 'ProfileController::index');
  $routes->get('warehouse-addresses', 'ProfileController::addresses');
  $routes->get('thank-you', 'ProfileController::thankYou');
  $routes->get('requests', 'ProfileController::myRequests');
  $routes->get('requests/edit/(:num)', 'ProfileController::edit/$1');
  $routes->get('requests/view/(:num)', 'ProfileController::view/$1');
  $routes->post('requests/update/(:num)', 'ProfileController::update/$1');
 }
);

$routes->group('addresses', ['filter' => 'role:customer', 'namespace' => 'App\Controllers'], function ($routes) {
 $routes->get('/', 'AddressController::index');
 $routes->get('create', 'AddressController::create');
 $routes->post('store', 'AddressController::store');
 $routes->get('edit/(:num)', 'AddressController::edit/$1');
 $routes->post('update/(:num)', 'AddressController::update/$1');
 $routes->get('delete/(:num)', 'AddressController::delete/$1');
 $routes->get('primary/(:num)', 'AddressController::setDefault/$1');
});

//page buildder
$routes->group('pagebuilder', ['namespace' => 'App\Controllers'], function ($routes) {
 $routes->get('index/(:num)', 'PageBuilder::index/$1'); // Edit page with ID
 $routes->get('load/(:num)', 'PageBuilder::load/$1'); // load page with ID
 $routes->post('save', 'PageBuilder::save'); // Save page content
});
