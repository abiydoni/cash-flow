<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ─── PUBLIC ROUTES ────────────────────────────────────────────────────────────
$routes->get('/', function () {
    return redirect()->to('/auth/login');
});

$routes->get('lang/(:alpha)', 'LanguageController::index/$1');

$routes->group('auth', function ($routes) {
    $routes->get('login',             'Auth::login');
    $routes->post('login',            'Auth::loginProcess');
    $routes->get('register',          'Auth::register');
    $routes->post('register',         'Auth::registerProcess');
    $routes->get('logout',            'Auth::logout');
});

// ─── AUTHENTICATED ROUTES ─────────────────────────────────────────────────────
$routes->group('', ['filter' => 'auth'], function ($routes) {

    // Dashboard
    $routes->get('dashboard', 'Dashboard::index');

    // Transactions
    $routes->get('transaction',            'Transaction::index');
    $routes->get('transaction/create',     'Transaction::create');
    $routes->post('transaction/store',     'Transaction::store');
    $routes->get('transaction/edit/(:num)',    'Transaction::edit/$1');
    $routes->post('transaction/update/(:num)', 'Transaction::update/$1');
    $routes->delete('transaction/(:num)',      'Transaction::delete/$1');
    $routes->post('transaction/delete/(:num)', 'Transaction::delete/$1'); // fallback POST
    $routes->get('transaction/summary',        'Transaction::summary');

    // Profile
    $routes->get('profile',               'Profile::index');
    $routes->post('profile/update',       'Profile::update');
    $routes->post('profile/password',     'Profile::changePassword');

    // Categories (Personal)
    $routes->get('category',              'Category::index');
    $routes->post('category/store',       'Category::store');
    $routes->post('category/delete/(:num)','Category::delete/$1');
});

// ─── ADMIN ROUTES ─────────────────────────────────────────────────────────────
$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    $routes->get('users',                   'Admin::users');
    $routes->post('users/toggle/(:num)',     'Admin::toggleUser/$1');
    $routes->post('users/delete/(:num)',     'Admin::deleteUser/$1');

    $routes->get('transactions',            'Admin::transactions');

    $routes->get('categories',              'Admin::categories');
    $routes->post('categories/store',       'Admin::storeCategory');
    $routes->post('categories/delete/(:num)', 'Admin::deleteCategory/$1');
});
