<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ---------------------------------------------------------------
// WEBSITE (Public) Routes
// ---------------------------------------------------------------
$routes->get('/', 'WebsiteController::quiz');        // default page shows the questions
$routes->get('/home', 'WebsiteController::index');   // landing page
$routes->get('/quiz', 'WebsiteController::quiz');
$routes->get('/quiz/(:num)', 'WebsiteController::quiz/$1');
$routes->get('/text-quiz', 'WebsiteController::textQuiz');   // image + text-answer questions
$routes->post('/quiz/submit', 'WebsiteController::submit');
$routes->get('/results/(:alphanum)', 'WebsiteController::results/$1');
$routes->get('/about', 'WebsiteController::about');

// ---------------------------------------------------------------
// ADMIN Routes (prefix: /admin)
// ---------------------------------------------------------------
// Admin login (public)
$routes->get('admin/login', 'AuthController::login');
$routes->post('admin/login', 'AuthController::attemptLogin');
$routes->get('admin/logout', 'AuthController::logout');

// Admin panel (login required)
$routes->group('admin', ['namespace' => 'App\Controllers', 'filter' => 'adminauth'], function ($routes) {
    // Dashboard
    $routes->get('/', 'AdminController::index');
    $routes->get('dashboard', 'AdminController::index');

    // Questions CRUD
    $routes->get('questions', 'AdminController::questions');
    $routes->get('questions/create', 'AdminController::createQuestion');
    $routes->post('questions/store', 'AdminController::storeQuestion');
    $routes->get('questions/edit/(:num)', 'AdminController::editQuestion/$1');
    $routes->post('questions/update/(:num)', 'AdminController::updateQuestion/$1');
    $routes->post('questions/delete/(:num)', 'AdminController::deleteQuestion/$1');
    $routes->post('questions/reorder', 'AdminController::reorderQuestions');

    // Text questions (own tables: text_questions / text_question_options)
    $routes->get('text-questions', 'AdminController::textQuestions');
    $routes->get('text-questions/create', 'AdminController::createTextQuestion');
    $routes->post('text-questions/store', 'AdminController::storeTextQuestion');
    $routes->get('text-questions/edit/(:num)', 'AdminController::editTextQuestion/$1');
    $routes->post('text-questions/update/(:num)', 'AdminController::updateTextQuestion/$1');
    $routes->post('text-questions/delete/(:num)', 'AdminController::deleteTextQuestion/$1');

    // Results / Submissions
    $routes->get('results', 'AdminController::results');
    $routes->get('results/(:alphanum)', 'AdminController::resultDetail/$1');
    $routes->post('results/clear', 'AdminController::clearResults');

    // Settings
    $routes->get('settings', 'AdminController::settings');
    $routes->post('settings/save', 'AdminController::saveSettings');
    $routes->post('settings/password', 'AdminController::changePassword');

    // API endpoints (AJAX)
    $routes->post('api/toggle-question/(:num)', 'AdminController::toggleQuestion/$1');
    $routes->post('api/toggle-text-question/(:num)', 'AdminController::toggleTextQuestion/$1');
});
