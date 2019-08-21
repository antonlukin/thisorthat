<?php
/**
 * Connect routes with API methods
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License
 * @since       2.0
 */


/**
 * Register new user and get user_id and token
 *
 * @link https://docs.thisorthat.ru/#register
 */
Flight::route('POST /register', [
    'methods\register', 'run_task'
], true);


/**
 * Use this method to get a list of items.
 *
 * @link https://docs.thisorthat.ru/#getitems
 */
Flight::route('GET|POST /getItems', [
    'methods\getItems', 'run_task'
], true);


/**
 * Use this method to get all certain user added items.
 *
 * @link https://docs.thisorthat.ru/#getmyitems
 */
Flight::route('GET|POST /getMyItems', [
    'methods\getMyItems', 'run_task'
], true);


/**
 * Add new item with first and last texts
 *
 * @link https://docs.thisorthat.ru/#additem
 */
Flight::route('GET|POST /addItem', [
    'methods\addItem', 'run_task'
], true);


/**
 *
 */
Flight::route('GET|POST /setViewed', [
    'methods\setViewed', 'run_task'
], true);


/**
 *
 */
Flight::route('GET|POST /get-comments', [
    'methods\getComments', 'run_task'
], true);


/**
 *
 */
Flight::route('GET|POST /add-comments', [
    'methods\addComments', 'run_task'
], true);


/**
 *
 */
Flight::route('GET|POST /send-report', [
    'methods\sendReport', 'run_task'
], true);
