<?php
/**
 * Model for /register API method
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License, https://github.com/antonlukin/thisorthat/blob/master/LICENSE
 * @since       2.0
 */

namespace methods;


/**
 * Register new user and get user_id and token
 */
class register extends \engine
{
    /**
     * Get last user section field
     */
    private static function get_section()
    {
        $database = parent::get_database();

        // Try to get last user section
        $select = $database->query("SELECT section FROM users ORDER BY id DESC LIMIT 1");

        return $select->fetchColumn();
    }


    /**
     * Insert new user into users table
     */
    private static function insert_user($secret, $uniqid, $client)
    {
        $database = parent::get_database();

        // Get last user section
        $section = self::get_section();

        // Reset section value if too big
        if ($section === false || $section > parent::$sections) {
            $section = 0;
        }

        $section = intval($section) + 1;

        // The query to insert user object
        $query = "INSERT INTO users (`secret`, `uniqid`, `client`, `section`)
            VALUES (:secret, :uniqid, :client, :section)";

        $insert = $database->prepare($query);
        $insert->execute(compact('secret', 'uniqid', 'client', 'section'));

        return $database->lastInsertId();
    }


    /**
     * Model entry point
     */
    public static function run_task()
    {
        // Load engine from parent class
        parent::load_config();

        $client = parent::get_parameter('client', '^[a-z0-9-_]{0,16}$');

        // Check client parameter
        if ($client === false) {
            parent::show_error('Параметр client не соответствует условиям', 400);
        }

        $uniqid = parent::get_parameter('uniqid', '^[a-z0-9-_]{0,64}$');

        // Check uniqid parameter
        if ($uniqid === false) {
            parent::show_error('Параметр uniqid не соответствует условиям', 400);
        }

        // Generate random token
        $marker = md5(uniqid());

        // Get secret using random marker
        $secret = parent::get_secret($marker);

        // Make sql query
        $user_id = self::insert_user($secret, $uniqid, $client);

        // Compose token with leading user id divided with colon
        $token = implode(':', [$user_id, $marker]);

        parent::show_success(compact('token'));
    }
}

