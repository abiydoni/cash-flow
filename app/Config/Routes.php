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

    // Dues (Authenticated users)
    $routes->get('dues',                'Dues::index');
    $routes->get('dues/type/(:num)',    'Dues::type/$1');
    $routes->get('dues/member/(:num)',  'Dues::detail/$1');
    $routes->post('dues/pay',           'Dues::pay');
    $routes->post('dues/delete/(:num)', 'Dues::delete/$1');

    // Members (Per User)
    $routes->get('member',               'Member::index');
    $routes->post('member/store',        'Member::store');
    $routes->post('member/delete/(:num)','Member::delete/$1');
    $routes->post('member/toggle/(:num)','Member::toggle/$1');

    // Dues Types (Per User)
    $routes->get('duestype',               'DuesType::index');
    $routes->post('duestype/store',        'DuesType::store');
    $routes->post('duestype/delete/(:num)','DuesType::delete/$1');

    // Reports
    $routes->get('report',                 'Report::index');
    $routes->get('report/month/(:num)/(:num)', 'Report::viewMonth/$1/$2');
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

    // Settings & Reset
    $routes->get('settings',                'Settings::index');
    $routes->get('settings/backup',         'Settings::backup');
    $routes->post('settings/notifications', 'Settings::updateNotifications');
    $routes->post('settings/reset',         'Settings::resetData');
});
