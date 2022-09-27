<?php
/**
 * Audit new questions.
 * Get items with more than 5 votes and approve or decline them.
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License
 * @since       3.0
 */

require_once(__DIR__ . '/../vendor/autoload.php');


/**
 * Connect to database and return its instance
 */
function get_database() {
    $dotenv = Dotenv\Dotenv::create(dirname(__DIR__));
    $dotenv->load();

    // Check required options
    $dotenv->required([
        'DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'REDIS_HOST', 'REDIS_PREFIX'
    ]);


    $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8mb4";

    $settings = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_STRINGIFY_FETCHES => true
    ];

    return new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], $settings);
}


/**
 * Get items with more than 3 reports
 */
function get_items($database) {
    $query = "SELECT items.id FROM reports, items
        WHERE items.id = reports.item_id
        AND items.status <> 'rejected'
        GROUP BY item_id HAVING COUNT(*) > 2";

    $select = $database->query($query);
    return $select->fetchAll();
}


/**
 * Delete item
 */
function reject_item($database, $id) {
    $query = "UPDATE items SET status = 'rejected' WHERE id = :id";

    $update = $database->prepare($query);
    $update->execute(compact('id'));
}


{
    $results = [];

    // Get database instance
    $database = get_database();

    foreach (get_items($database) as $row) {
        reject_item($database, $row->id);
    }
}

