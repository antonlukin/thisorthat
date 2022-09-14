<?php
/**
 * Custom API for This or That service
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License
 * @since       2.0
 */

/**
 * Register composer auto loader
 */
require_once(__DIR__ . '/../vendor/autoload.php');


/**
 * Set root path variable
 */
Flight::set('config.root_path', __DIR__);


/**
 * Log errors to the web server's error log file
 */
Flight::set('flight.log_errors', true);


/**
 * Autoload classes
 */
Flight::path(__DIR__ . '/classes/');


/**
 * Remap default errors
 */
Flight::map('error', function (Exception $exception) {
    Flight::json(['ok' => false, 'description' => 'Ошибка сервера'], 500);
    exit;
});

Flight::map('notFound', function () {
    Flight::json(['ok' => false, 'description' => 'Метод не найден'], 404);
    exit;
});


/**
 * We are ready to handle requests
 */
require_once(__DIR__ . '/routes.php');


/**
 * Start application with Flight
 *
 * @link http://flightphp.com/
 */
Flight::start();
