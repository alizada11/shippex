<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/admin', 'Admin::index');
$routes->get('/login', 'Auth::login');
$routes->post('/login-post', 'Auth::loginPost');
$routes->get('/logout', 'Auth::logout');
$routes->get('/admin', 'Admin::index', ['filter' => 'authGuard']);
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::registerPost');
$routes->get('/forgot', 'Auth::forgot');
$routes->post('/forgot', 'Auth::forgotPost');
$routes->get('/reset-password/(:any)', 'Auth::reset/$1');
$routes->post('/reset-password/(:any)', 'Auth::resetPost/$1');
$routes->get('/change-password', 'Auth::changePassword');
$routes->post('/change-password', 'Auth::changePasswordPost');
// services
$routes->get('/services', 'Services::index');
