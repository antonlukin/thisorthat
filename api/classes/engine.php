<?php
/**
 * Core API class
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License, https://github.com/antonlukin/thisorthat/blob/master/LICENSE
 * @since       2.0
 */


/**
 * The engine class contains the core functionality of the API.
 * It registers all required classes and modules using Flight framework
 */
class engine
{
    /**
     * Remap default errors and load .env config
     */
    protected static function load_config()
    {
        /*
        Flight::map('error', function (Exception $exception) {
            echo $exception->getTraceAsString();
        });

        Flight::map('notFound', function () {
           Flight::halt(404, '12');
        });
         */

        // Get application root path
        $root_path = Flight::get('config.root_path');

        // Try to load dotenv
        $dotenv = Dotenv\Dotenv::create(dirname($root_path));
        $dotenv->load();

        // Check required options
        $dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);
    }


    /**
     * Create database instance
     */
    protected static function connect_database()
    {
        // Create dsn connection string
        $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8";

        // Create PDO instance with options and credentials
        $database = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);

        // Set PDO options
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $database->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $database->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        return $database;
    }
}
