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
Flight::route('GET|POST /register', [
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
 * Use this method to get items that the current user has commented
 *
 * @link https://docs.thisorthat.ru/#getcommenteditems
 */
Flight::route('GET|POST /getCommentedItems', [
    'methods\getCommentedItems', 'run_task'
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
 * Add user votes for items
 *
 * @link https://docs.thisorthat.ru/#setviewed
 */
Flight::route('GET|POST /setViewed', [
    'methods\setViewed', 'run_task'
], true);


/**
 * Get favorite user items
 *
 * @link https://docs.thisorthat.ru/#getfavorite
 */
Flight::route('GET|POST /getFavorite', [
    'methods\getFavorite', 'run_task'
], true);


/**
 * Add item to user favorite list
 *
 * @link https://docs.thisorthat.ru/#addfavorite
 */
Flight::route('GET|POST /addFavorite', [
    'methods\addFavorite', 'run_task'
], true);


/**
 * Delete item from user favorite list
 *
 * @link https://docs.thisorthat.ru/#deletefavorite
 */
Flight::route('GET|POST /deleteFavorite', [
    'methods\deleteFavorite', 'run_task'
], true);


/**
 * Send report to item
 *
 * @link  https://docs.thisorthat.ru/#sendreport
 */
Flight::route('GET|POST /sendReport', [
    'methods\sendReport', 'run_task'
], true);


/**
 * Cancel report for item
 *
 * @link  https://docs.thisorthat.ru/#cancelreport
 */
Flight::route('GET|POST /cancelReport', [
    'methods\cancelReport', 'run_task'
], true);


/**
 * Get comments by item id
 *
 * @link https://docs.thisorthat.ru/#getcomments
 */
Flight::route('GET|POST /getComments', [
    'methods\getComments', 'run_task'
], true);


/**
 * Get users' comments by
 *
 * @link https://docs.thisorthat.ru/#getmycomments
 */
Flight::route('GET|POST /getMyComments', [
    'methods\getMyComments', 'run_task'
], true);


/**
 * Add comment to item
 *
 * @link https://docs.thisorthat.ru/#addcomment
 */
Flight::route('GET|POST /addComment', [
    'methods\addComment', 'run_task'
], true);


/**
 * Send report to comment
 *
 * @link https://docs.thisorthat.ru/#reportcomment
 */
Flight::route('GET|POST /reportComment', [
    'methods\reportComment', 'run_task'
], true);


/**
 * Get unaudited items for certain user
 *
 * @link https://docs.thisorthat.ru/#getaudit
 */
Flight::route('GET|POST /getAudit', [
    'methods\getAudit', 'run_task'
], true);


/**
 * Set audit vote for new items
 *
 * @link https://docs.thisorthat.ru/#setaudit
 */
Flight::route('GET|POST /setAudit', [
    'methods\setAudit', 'run_task'
], true);

