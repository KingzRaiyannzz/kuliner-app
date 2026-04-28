<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('places',          'PlaceController::index');
$routes->get('places/(:num)',   'PlaceController::show/$1');
$routes->post('places',         'PlaceController::store', ['filter' => 'auth']);
$routes->post('reviews',        'ReviewController::store', ['filter' => 'auth']);
$routes->get('geo/search',      'GeoController::search');

$routes->get('places/create',  'PlaceController::create');
$routes->post('places',        'PlaceController::store');
$routes->get('geo/search',     'GeoController::search');
$routes->get('geo/reverse',    'GeoController::reverse');

// Auth
$routes->get('login',    'AuthController::loginForm');
$routes->post('login',   'AuthController::login');
$routes->get('register', 'AuthController::registerForm');
$routes->post('register', 'AuthController::register');
$routes->get('logout',   'AuthController::logout');

// Places
$routes->get('/',                     'PlaceController::index');
$routes->get('places',                'PlaceController::index');
$routes->get('places/create',         'PlaceController::create');
$routes->post('places',               'PlaceController::store');
$routes->get('places/(:num)',         'PlaceController::show/$1');
$routes->get('places/(:num)/edit',    'PlaceController::edit/$1');
$routes->post('places/(:num)/update', 'PlaceController::update/$1');
$routes->post('places/(:num)/delete', 'PlaceController::delete/$1');

// Reviews
$routes->post('reviews',              'ReviewController::store');
$routes->post('reviews/(:num)/delete', 'ReviewController::destroy/$1');

// Geo (Nominatim proxy)
$routes->get('geo/search',  'GeoController::search');
$routes->get('geo/reverse', 'GeoController::reverse');
