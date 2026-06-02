<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ================================================================
// PUBLIC ROUTES — bisa diakses siapa saja tanpa login
// ================================================================
$routes->get('/',                   'PlaceController::index');
$routes->get('places',              'PlaceController::index');
$routes->get('places/(:num)',       'PlaceController::show/$1');

// Auth
$routes->get('login',               'AuthController::loginForm');
$routes->post('login',              'AuthController::login');
$routes->get('register',            'AuthController::registerForm');
$routes->post('register',           'AuthController::register');
$routes->get('logout',              'AuthController::logout');

// Geo proxy (Nominatim) — public karena dipakai di form tambah tempat
$routes->get('geo/search',          'GeoController::search');
$routes->get('geo/reverse',         'GeoController::reverse');


// ================================================================
// KONTRIBUTOR ROUTES — harus login + role kontributor atau admin
// ================================================================
$routes->group('', ['filter' => 'kontributor'], function ($routes) {

    // Tempat kuliner — tambah & edit milik sendiri
    $routes->get('places/create',           'PlaceController::create');
    $routes->post('places',                 'PlaceController::store');
    $routes->get('places/(:num)/edit',      'PlaceController::edit/$1');
    $routes->post('places/(:num)/update',   'PlaceController::update/$1');
    $routes->post('places/(:num)/delete',   'PlaceController::delete/$1');

    // Review
    $routes->post('reviews',                'ReviewController::store');
    $routes->post('reviews/(:num)/delete',  'ReviewController::destroy/$1');
});


// ================================================================
// ADMIN ROUTES — hanya admin yang bisa akses
// ================================================================
$routes->group('admin', ['filter' => 'admin'], function ($routes) {

    // Dashboard
    $routes->get('/',                       'Admin\DashboardController::index');

    // Kelola kategori
    $routes->get('categories',              'Admin\CategoryController::index');
    $routes->post('categories',             'Admin\CategoryController::store');
    $routes->post('categories/(:num)/delete', 'Admin\CategoryController::destroy/$1');

    // Kelola tag
    $routes->get('tags',                    'Admin\TagController::index');
    $routes->post('tags',                   'Admin\TagController::store');
    $routes->post('tags/(:num)/delete',     'Admin\TagController::destroy/$1');

    // Moderasi tempat (approve / reject)
    $routes->get('places',                  'Admin\PlaceController::index');
    $routes->post('places/(:num)/verify',   'Admin\PlaceController::verify/$1');
    $routes->post('places/(:num)/delete',   'Admin\PlaceController::destroy/$1');

    // Moderasi review
    $routes->get('reviews',                 'Admin\ReviewController::index');
    $routes->post('reviews/(:num)/delete',  'Admin\ReviewController::destroy/$1');

    // Kelola user
    $routes->get('users',                   'Admin\UserController::index');
    $routes->post('users/(:num)/role',      'Admin\UserController::updateRole/$1');

    // Tambahkan baris ini
    $routes->post('places/(:num)/verify',       'Admin\PlaceController::verify/$1');
    $routes->post('places/(:num)/delete',       'Admin\PlaceController::destroy/$1');
});
