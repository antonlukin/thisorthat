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
 * Get items
 */
function get_items($database) {
    $query = "SELECT * FROM items";

    $select = $database->query($query);
    return $select->fetchAll();
}

/**
 * Get item reports
 */
function get_reports($database, $item) {
    $query = "SELECT COUNT(*) AS amount FROM reports WHERE item_id = :item";

    $select = $database->prepare($query);
    $select->execute(['item' => $item]);

    $row = $select->fetch();

    return $row->amount;
}


/**
 * Get item favorites
 */
function get_favorites($database, $item) {
    $query = "SELECT COUNT(*) AS amount FROM favorite WHERE item_id = :item";

    $select = $database->prepare($query);
    $select->execute(['item' => $item]);

    $row = $select->fetch();

    return $row->amount;
}


{
    $results = [];

    // Get database instance
    $database = get_database();

    foreach (get_items($database) as $item) {
        $results[$item->id] = [
            'first_text' => $item->first_text,
            'last_text'  => $item->last_text,
            'likes'      => get_favorites($database, $item->id),
            'dislikes'   => get_reports($database, $item->id),
            'status'     => $item->status,
        ];
    }

    file_put_contents(__DIR__ . '/bestie.json', json_encode($results, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
}

