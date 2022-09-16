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
 * Get audit items with more than 5 votes
 */
function get_items($database) {
    $query = "SELECT item_id AS id, vote FROM audit
        WHERE item_id IN (SELECT item_id FROM items, audit
        WHERE items.id = audit.item_id AND status = 'new'
        GROUP BY items.id
        HAVING COUNT(*) >= 5)";

    $select = $database->query($query);
    return $select->fetchAll();
}


/**
 * Set item status
 */
function update_item($database, $id, $status) {
    $query = "UPDATE items SET status = :status WHERE id = :id";

    $update = $database->prepare($query);
    $update->execute(compact('status', 'id'));
}


{
    $results = [];

    // Get database instance
    $database = get_database();

    foreach (get_items($database) as $row) {
        if (!isset($results[$row->id])) {
            $results[$row->id] = 0;
        }

        if ($row->vote === 'approve') {
            $results[$row->id] = $results[$row->id] + 1;
        }

        if ($row->vote === 'decline') {
            $results[$row->id] = $results[$row->id] - 2;
        }
    }

    foreach ($results as $id => $result) {
        $status = 'approved';

        if ($result < 1) {
            $status = 'rejected';
        }

        update_item($database, $id, $status);
    }
}

