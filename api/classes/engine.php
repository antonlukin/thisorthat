<?php
/**
 * Core API class
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License
 * @since       2.0
 */


/**
 * The engine class contains the core functionality of the API.
 * It registers all required classes and modules using Flight framework
 */
class engine
{
    /**
     * Database instance
     */
    protected static $database = null;


    /**
     * Redis instance
     */
    protected static $redis = null;


    /**
     * Redis cache prefix
     */
    protected static $redis_prefix = '';


    /**
     * All items are divided into sections.
     * This variable stores total sections count
     */
    protected static $sections = 1000;


    /**
     * Load config form .env file
     */
    protected static function load_config()
    {
        // Get application root path
        $root_path = Flight::get('config.root_path');

        // Try to load dotenv
        $dotenv = Dotenv\Dotenv::create(dirname($root_path));
        $dotenv->load();

        // Check required options
        $dotenv->required([
            'DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'REDIS_HOST', 'REDIS_PREFIX'
        ]);

        // Set redix prefix
        self::$redis_prefix = $_ENV['REDIS_PREFIX'];
    }


    /**
     * Create redis instance
     */
    protected static function get_redis()
    {
        // Connect redis if empty instance
        if (self::$redis === null) {
            $redis = new Redis();

            $redis->connect($_ENV['REDIS_HOST']);
            $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);

            self::$redis = $redis;
        }

        return self::$redis;
    }


    /**
     * Create database instance
     */
    protected static function get_database()
    {
        // Connect database if empty instance
        if (self::$database === null) {
            $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8";

            // Set PDO options
            $settings = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            // Create PDO instance with options and credentials
            self::$database = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], $settings);
        }

        return self::$database;
    }


    /**
     * Try to authenticate user
     */
    protected static function authorize_user()
    {
        $token = self::get_parameter('token', '^\d+:[a-z0-9]{32}$');

        // Check token parameter
        if ($token === false) {
            self::show_error('Authorization required', 401);
        }

        list($user_id, $marker) = explode(':', $token);

        // Get secret
        $secret = self::get_secret($marker);

        // Get database instance
        $database = self::get_database();

        $select = $database->prepare("SELECT id FROM users WHERE id = :user_id AND secret = :secret LIMIT 1");
        $select->execute(compact('user_id', 'secret'));

        if ($select->fetch() === false) {
            self::show_error('Authorization failed', 401);
        }

        return $user_id;
    }


    /**
     * Get secret from marker
     */
    protected static function get_secret($marker)
    {
        return substr(hash('sha256', $marker), 10, 32);
    }


    /**
     * Get request query parameter
     */
    protected static function get_parameter($name, $regex, $default = false)
    {
        $request = array_merge($_GET, $_POST);

        if (isset($request[$name]) && preg_match("/{$regex}/ui", $request[$name])) {
            return $request[$name];
        }

        return $default;
    }


    /**
     * Show json error with custom http code
     */
    protected static function show_error($description, $code)
    {
        Flight::json(['ok' => false, 'description' => $description], $code);
        exit;
    }


    /**
     * Show json success with custom http code
     */
    protected static function show_success($result)
    {
        Flight::json(['ok' => true, 'result' => $result], 200);
        exit;
    }
}
