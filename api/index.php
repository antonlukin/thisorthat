<?php
/**
 * Custom API for This or That service
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License, https://github.com/antonlukin/thisorthat/blob/master/LICENSE
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
 * Autoload classes
 */
Flight::path(__DIR__ . '/classes/');


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
