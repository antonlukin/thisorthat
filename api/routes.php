<?php
/**
 * Connect routes with API methods
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License, https://github.com/antonlukin/thisorthat/blob/master/LICENSE
 * @since       2.0
 */


/**
 *
 */
Flight::route('GET|POST /registerUser', [
    'methods\registerUser', 'run'
], true);


/**
 * Use this method to get a list of items.
 * On success, returns an Array of Items objects.
 */
Flight::route('GET|POST /', [
    'methods\getItems', 'run'
], true);


/**
 *
 */
Flight::route('GET|POST /getMyItems', [
    'methods\getMyItems', 'run'
], true);


/**
 *
 */
Flight::route('GET|POST /showItems', [
    'methods\showItems', 'run'
], true);


/**
 *
 */
Flight::route('GET|POST /addItems', [
    'methods\addItems', 'run'
], true);


/**
 *
 */
Flight::route('GET|POST /addViews', [
    'methods\addViews', 'run'
], true);


/**
 *
 */
Flight::route('GET|POST /getComments', [
    'methods\getComments', 'run'
], true);


/**
 *
 */
Flight::route('GET|POST /addComments', [
    'methods\addComments', 'run'
], true);


/**
 *
 */
Flight::route('GET|POST /sendReport', [
    'methods\sendReport', 'run'
], true);
