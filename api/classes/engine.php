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
    protected static $sections = 500;


    /**
     * Add user avatars endpoint
     */
    protected static $avatars = 'https://image.thisorthat.ru/100/';


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
            $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8mb4";

            // Set PDO options
            $settings = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => true
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
            self::show_error('Не удалось авторизовать запрос', 401);
        }

        list($user_id, $marker) = explode(':', $token);

        // Get secret
        $secret = self::get_secret($marker);

        // Get database instance
        $database = self::get_database();

        $select = $database->prepare("SELECT id FROM users WHERE id = :user_id AND secret = :secret LIMIT 1");
        $select->execute(compact('user_id', 'secret'));

        if ($select->fetch() === false) {
            self::show_error('Не удалось авторизовать запрос', 401);
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

        // Can be array also
        if (isset($request[$name]) && is_string($request[$name])) {
            $parameter = $request[$name];

            if (preg_match("/{$regex}/uis", $parameter)) {
                return $parameter;
            }
        }

        return $default;
    }


    /**
     * Get request query array
     */
    protected static function get_array($name, $default = [])
    {
        $request = array_merge($_GET, $_POST);

        // Should be array type
        if (isset($request[$name]) && is_array($request[$name])) {
            return array_filter($request[$name]);
        }

        return $default;
    }


    /**
     * Check bad words using external class
     */
    protected static function has_badwords($text)
    {
        $badwords = Censure\Censure::parse($text);

        // On error this method returns int error code
        if ($badwords === false || !is_string($badwords)) {
            return false;
        }

        return true;
    }


    /**
     * Sanitize text replacing extra chars and spaces
     */
    protected static function sanitize_text($text)
    {
        // Remove double chars
        $text = preg_replace('#([?!.:,:])\1+#', '$1', $text);

        // Remove extra spaces
        $text = trim(preg_replace('#\s{2,}#', '', $text));

        return $text;
    }


    /**
     * Show json error with custom http code
     */
    protected static function show_error($description, $code = 500, $parameters = null)
    {
        $message = ['ok' => false, 'description' => $description];

        if ($parameters !== null) {
            $message['parameters'] = $parameters;
        }

        Flight::json($message, $code, true, 'utf-8', JSON_UNESCAPED_UNICODE);
        exit;
    }


    /**
     * Show json success with custom http code
     */
    protected static function show_success($result, $code = 200)
    {
        $message = ['ok' => true, 'result' => $result];

        Flight::json($message, $code, true, 'utf-8', JSON_UNESCAPED_UNICODE);
        exit;
    }
}
