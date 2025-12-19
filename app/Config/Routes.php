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
$routes->get('user/info/(:num)', 'Auth::getUserInfo/$1');


//shipping services
// CRUD resource routes (maps to index, show, create, update, delete)
$routes->get('shipping-services/create', 'ShippingServices::create_form');
$routes->get('shipping-services', 'ShippingServices::index');
$routes->get('shipping-services/(:num)', 'ShippingServices::show/$1');
$routes->post('shipping-services', 'ShippingServices::create');
$routes->put('shipping-services/(:num)', 'ShippingServices::update/$1');
$routes->patch('shipping-services/(:num)', 'ShippingServices::update/$1');
$routes->post('shipping-services/(:num)', 'ShippingServices::update/$1');
$routes->delete('shipping-services/(:num)', 'ShippingServices::delete/$1');
$routes->post('shipping-services/import-preview/(:num)', 'ShippingServices::importPreview/$1');
$routes->get('shipping-services/get_all/(:num)', 'ShippingServices::getAll/$1');
// bulk import of pasted html
$routes->post('shipping-services/import-html/(:num)', 'ShippingServices::importHtml/$1');
$routes->post('shipping-services/import-single/(:num)', 'ShippingServices::importSingle/$1');
$routes->post('shipping-services/set-price/', 'ShippingServices::setPrice');
$routes->post('shipping-services/manual-insert', 'ShippingServices::manualInsert');


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
$routes->post('/update-profile', 'Auth::updateProfile');
$routes->get('verify-email/(:any)', 'Auth::verifyEmail/$1');

/* *****************************************************************
*                      admin routes                                *
*******************************************************************/
$routes->get('/dashboard', 'Admin::index',  ['filter' => 'authGuard:admin']);
$routes->get('/test-cookie', 'Admin::testCookie');

// warehouses
$routes->group(
 '',
 ['filter' => 'role:admin', 'namespace' => 'App\Controllers'],
 function ($routes) {
  //users
  $routes->get('/users', 'Auth::index');
  $routes->get('/users/profile/(:num)', 'Auth::userProfile/$1');

  // warehouse
  $routes->get('/warehouse', 'WarehouseController::index');
  $routes->get('/warehouse/create', 'WarehouseController::create');
  $routes->post('/warehouse/store', 'WarehouseController::store');
  $routes->get('/warehouse/edit/(:num)', 'WarehouseController::edit/$1');
  $routes->post('/warehouse/update/(:num)', 'WarehouseController::update/$1');
  $routes->post('/warehouse/delete/(:num)', 'WarehouseController::delete/$1');

  // Shipping reques
  $routes->get('/shipping/requests', 'Shipping::requests');
  $routes->get('/shipping/details/(:num)', 'Shipping::details/$1');
  $routes->post('/shipping/delete/(:num)', 'Shipping::destroy/$1');
  $routes->post('/shipping/update-status/(:num)', 'Shipping::updateStatus/$1');
  $routes->post('/shipping/update-label/(:num)', 'Shipping::updateLabel/$1');
  $routes->post('/shipping/delete-label/(:num)', 'Shipping::deleteLabel/$1');

  // admin shopper routes
  $routes->get('admin/shopper/requests', 'Admin::shopperRequests');
  $routes->get('admin/shopper/requests/edit/(:num)', 'Admin::edit/$1');
  $routes->get('admin/shopper/requests/view/(:num)', 'Admin::shopperView/$1');
  $routes->post('admin/shopper/requests/set_price', 'Admin::set_price');
  $routes->post('admin/shopper-requests/mark-shipped', 'Shopper::markShipped');
  $routes->get('admin/shopper-requests/get-items', 'Shopper::getItems');
 }
);
// overdue fee payment
$routes->post('package/payOverdueFee/(:num)', 'PackageController::payOverdueFee/$1');
$routes->get('package/completePayment/(:num)', 'PackageController::completePayment/$1');
$routes->get('package/cancelPayment/(:num)', 'PackageController::cancelPayment/$1');


$routes->group('packages', ['filter' => 'role:admin,customer', 'namespace' => 'App\Controllers'], function ($routes) {
 $routes->get('(:num)', 'PackageController::index/$1');
 $routes->get('create/(:num)', 'PackageController::create/$1');
 $routes->post('store', 'PackageController::store');
 $routes->get('show/(:num)', 'PackageController::show/$1');
 $routes->get('(:num)/edit', 'PackageController::edit/$1');
 $routes->post('(:num)/update', 'PackageController::update/$1');
 $routes->get('(:num)/reject_file', 'PackageController::rejectFile/$1');
 $routes->post('(:num)/delete', 'PackageController::delete/$1');
 $routes->get('shipping-categories', 'PackageController::getShippingCategories');


 $routes->post('(:num)/items/add', 'PackageController::addItem/$1');
 $routes->post('items/update/(:num)', 'PackageController::updateItem/$1');
 $routes->get('items/delete/(:num)', 'PackageController::deleteItem/$1');

 $routes->post('(:num)/files/upload', 'PackageController::uploadFile/$1');
 $routes->post('files/delete/(:num)', 'PackageController::deleteFile/$1');
 $routes->post('shipping-data', 'PackageController::getShippingData');
 // $routes->get('shipping-data', 'PackageController::getShippingData');
 $routes->post('getRates', 'PackageController::getRates');
 $routes->post('combine-request', 'CombineRepackController::submitRequest');
});


$routes->get('virtual-addresses', 'PackageController::getVirtualAddresses', ['filter' => 'role:admin,customer']);


//notiications
$routes->group('',  static function ($routes) {
 $routes->get('notifications/all', 'NotificationController::all');
 $routes->get('notifications', 'NotificationController::index');
 $routes->get('notifications/unread', 'NotificationController::getUnreadNotifications');
 $routes->get('notifications/unread/count', 'NotificationController::getUnreadCount');
 $routes->post('notifications/read/(:num)', 'NotificationController::markAsRead/$1');
 $routes->post('notifications/read-all', 'NotificationController::markAllAsRead');
 $routes->delete('notifications/delete/(:num)', 'NotificationController::delete/$1');
 $routes->delete('notifications/delete-all', 'NotificationController::deleteAll');
});

// faqs
$routes->get('faqs', 'Admin\FaqController::faqs');
$routes->get('customers/faqs', 'CustomersController::faqs');
$routes->group('admin/faqs', ['filter' => 'role:admin', 'namespace' => 'App\Controllers\Admin'], function ($routes) {
 $routes->get('/', 'FaqController::index');
 $routes->get('create', 'FaqController::create');
 $routes->post('store', 'FaqController::store');
 $routes->get('edit/(:num)', 'FaqController::edit/$1');
 $routes->post('update/(:num)', 'FaqController::update/$1');
 $routes->post('delete/(:num)', 'FaqController::delete/$1');
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
  $routes->post('cms/how-it-works/delete/(:num)', 'Admin\HowItWorksController::delete/$1');
  // home locations
  $routes->get('cms/locations', 'Admin\LocationController::index');
  $routes->get('cms/locations/create', 'Admin\LocationController::create');
  $routes->post('cms/locations/store', 'Admin\LocationController::store');
  $routes->get('cms/locations/edit/(:num)', 'Admin\LocationController::edit/$1');
  $routes->post('cms/locations/update/(:num)', 'Admin\LocationController::update/$1');
  $routes->post('cms/locations/delete/(:num)', 'Admin\LocationController::delete/$1');
  // Deliverred today
  $routes->get('cms/delivered-today', 'Admin\DeliveredToday::index');
  $routes->get('cms/delivered-today/create', 'Admin\DeliveredToday::create');
  $routes->post('cms/delivered-today/store', 'Admin\DeliveredToday::store');
  $routes->get('cms/delivered-today/edit/(:num)', 'Admin\DeliveredToday::edit/$1');
  $routes->post('cms/delivered-today/update/(:num)', 'Admin\DeliveredToday::update/$1');
  $routes->post('cms/delivered-today/delete/(:num)', 'Admin\DeliveredToday::delete/$1');

  // promotions
  $routes->get('cms/promo-cards', 'Admin\PromoCards::index');
  $routes->get('cms/promo-cards/create', 'Admin\PromoCards::create');
  $routes->post('cms/promo-cards/store', 'Admin\PromoCards::store');
  $routes->get('cms/promo-cards/edit/(:num)', 'Admin\PromoCards::edit/$1');
  $routes->post('cms/promo-cards/update/(:num)', 'Admin\PromoCards::update/$1');
  $routes->post('cms/promo-cards/delete/(:num)', 'Admin\PromoCards::delete/$1');
  // how it works

  $routes->get('how-it-works', 'HowItWorks::admin_index');
  $routes->get('how-it-works/create', 'HowItWorks::create');
  $routes->post('how-it-works/store', 'HowItWorks::store');
  $routes->get('how-it-works/edit/(:num)', 'HowItWorks::edit/$1');
  $routes->post('how-it-works/update/(:num)', 'HowItWorks::update/$1');
  $routes->post('how-it-works/delete/(:num)', 'HowItWorks::delete/$1');

  // Steps routes
  $routes->get('cms/steps', 'Admin\Steps::index');
  $routes->get('cms/steps/create', 'Admin\Steps::create');
  $routes->post('cms/steps/create', 'Admin\Steps::store');
  $routes->get('cms/steps/edit/(:num)', 'Admin\Steps::edit/$1');
  $routes->post('cms/steps/edit/(:num)', 'Admin\Steps::update/$1');
  $routes->post('cms/steps/delete/(:num)', 'Admin\Steps::delete/$1');

  // Why Choose Us routes
  $routes->get('cms/why-choose', 'Admin\WhyChoose::index');
  $routes->get('cms/why-choose/create', 'Admin\WhyChoose::create');
  $routes->post('cms/why-choose/create', 'Admin\WhyChoose::store');
  $routes->get('cms/why-choose/edit/(:num)', 'Admin\WhyChoose::edit/$1');
  $routes->post('cms/why-choose/edit/(:num)', 'Admin\WhyChoose::update/$1');
  $routes->post('cms/why-choose/delete/(:num)', 'Admin\WhyChoose::delete/$1');
 }

);
$routes->group('', ['filter' => 'role:admin,customer'], function ($routes) {
 $routes->get('profile', 'ProfileController::index');
 // user modal / bulk info
 $routes->post('packages/bulk-info', 'DisposeReturnController::bulkInfo');
 $routes->post('packages/dispose-return-submit', 'DisposeReturnController::disposeSubmit');

 // Return Disposal Requests
 $routes->get('admin/dispose-return', 'DisposeReturnController::adminIndex');
 $routes->post('admin/dispose-return/process/(:num)', 'DisposeReturnController::process/$1');
 $routes->post('admin/dispose_return/delete/(:num)', 'DisposeReturnController::delete/$1');
 $routes->get('admin/dispose_return/edit/(:num)', 'DisposeReturnController::edit/$1');
 $routes->post('admin/dispose_return/process/(:num)', 'DisposeReturnController::update/$1');
 // combine requests
 $routes->get('admin/combine-requests', 'CombineRepackController::listRequests');
 $routes->get('admin/combine-requests/edit/(:num)', 'CombineRepackController::editRequest/$1');
 $routes->post('admin/combine-requests/update/(:num)', 'CombineRepackController::updateRequest/$1');
 $routes->post('admin/combine-requests/delete/(:num)', 'CombineRepackController::deleteRequest/$1');
});

$routes->get('/how-it-works', 'HowItWorks::index');


/* *****************************************************************
*                      customer routes                             *
*******************************************************************/
$routes->get('/customer/dashboard', 'CustomersController::dashboard', ['filter' => 'authGuard:customer']);


// warehouse requests
$routes->group('warehouse-requests', function ($routes) {
 $routes->get('', 'WarehouseRequestController::index');
 $routes->get('create', 'WarehouseRequestController::create');
 $routes->post('store', 'WarehouseRequestController::store');
 $routes->get('edit/(:num)', 'WarehouseRequestController::edit/$1');
 $routes->post('update/(:num)', 'WarehouseRequestController::update/$1');
 $routes->get('delete/(:num)', 'WarehouseRequestController::delete/$1');
 $routes->post('request', 'WarehouseRequestController::requestWarehouse');
 $routes->get('my-requests', 'WarehouseRequestController::myRequests');
 $routes->post('set-default', 'WarehouseRequestController::setDefault');
});
// shpping routes
$routes->group(
 '',
 ['filter' => 'role:customer', 'namespace' => 'App\Controllers'],
 function ($routes) {

  $routes->get('warehouse-addresses', 'ProfileController::addresses');
 }
);
$routes->group(
 'customer',
 ['filter' => 'role:customer,admin', 'namespace' => 'App\Controllers'],
 function ($routes) {
  $routes->get('shipping/requests', 'CustomersController::requests');
  $routes->get('shipping/details/(:num)', 'CustomersController::details/$1');
  $routes->post('shipping/delete/(:num)', 'CustomersController::destroy/$1');
  $routes->post('shipping/updateTracking', 'Shipping::updateTracking');
 }
);
$routes->group(
 'shopper',
 ['filter' => 'role:customer,admin', 'namespace' => 'App\Controllers'],
 function ($routes) {
  $routes->get('/', 'Shopper::index');
  $routes->post('submit', 'Shopper::submit');
  $routes->get('thank-you', 'Shopper::thankYou');
  $routes->get('requests', 'Shopper::myRequests');
  $routes->get('requests/edit/(:num)', 'Shopper::edit/$1');
  $routes->post('requests/delete/(:num)', 'Shopper::delete/$1');
  $routes->get('requests/view/(:num)', 'Shopper::view/$1');
  $routes->post('requests/update/(:num)', 'Shopper::update/$1');
  $routes->get('/shopper/view/(:num)', 'Shopper::view/$1');
 }
);
// search
$routes->post('search', 'SearchController::index');
$routes->get('search/live', 'SearchController::live');

// Frontend route
$routes->get('warehouse/(:segment)', 'WarehouseController::show/$1');

// Admin routes
$routes->group('admin/w_pages/', function ($routes) {
 $routes->get('/', 'Warehouse::index');
 $routes->get('create', 'Warehouse::create');
 $routes->post('store', 'Warehouse::store');
 $routes->get('edit/(:num)', 'Warehouse::edit/$1');
 $routes->post('update/(:num)', 'Warehouse::update/$1');
 $routes->post('delete/(:num)', 'Warehouse::delete/$1');
});

$routes->get('download/page', 'Download::page');
$routes->get('download/warehouse/(:num)', 'Download::warehouse/$1');

$routes->group(
 'profile',
 ['filter' => 'role:customer', 'namespace' => 'App\Controllers'],
 function ($routes) {

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
