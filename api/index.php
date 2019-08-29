<?php
/**
 * Custom API for This or That service
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License
 * @since       2.0
 */

file_put_contents('/tmp//thisorthat-request.log', 'get' . json_encode($_GET) . "\n", FILE_APPEND);
file_put_contents('/tmp//thisorthat-request.log', 'post' . json_encode($_POST) . "\n", FILE_APPEND);


/**
 * Register composer auto loader
 */
require_once(__DIR__ . '/../vendor/autoload.php');

// TODO: remove
Flight::set('config.time', microtime(true));


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
/*
Flight::map('error', function (Exception $exception) {
    Flight::json(['ok' => false, 'description' => 'Server internal error'], 500);
    exit;
});
 */

Flight::map('notFound', function () {
    Flight::json(['ok' => false, 'description' => 'Method not found'], 404);
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
