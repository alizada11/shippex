<?php

$routes->group('admin/blog', ['namespace' => 'Modules\\Blog\\Controllers\\Admin', 'filter' => 'authGuard:admin'], static function ($routes) {
 $routes->get('posts', 'PostController::index');
 $routes->get('posts/create', 'PostController::create');
 $routes->post('posts', 'PostController::store');
 $routes->get('posts/(:num)/edit', 'PostController::edit/$1');
 $routes->post('posts/(:num)', 'PostController::update/$1');
 $routes->post('posts/(:num)/delete', 'PostController::destroy/$1');
 $routes->post('upload-image', 'PostController::uploadImage');
});
$routes->group('filter/posts', ['namespace' => 'Modules\\Blog\\Controllers\\Admin', 'filter' => 'authGuard:admin'], static function ($routes) {
 $routes->get('all', 'PostController::index');
 $routes->get('published', 'PostController::published');
 $routes->get('draft', 'PostController::draft');
});


$routes->group('blog', ['namespace' => 'Modules\\Blog\\Controllers'], static function ($routes) {
 $routes->get('/', 'BlogController::index');
 $routes->get('category/(:num)', 'BlogController::category/$1');
 $routes->get('(:segment)', 'BlogController::show/$1');
});
