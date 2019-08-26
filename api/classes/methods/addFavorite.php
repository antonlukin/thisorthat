<?php
/**
 * Model for /addFavorite API method
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License
 * @since       2.0
 */

namespace methods;


/**
 * Add item to user favorite list
 */
class addFavorite extends \engine
{
    /**
     * Insert item to database
     */
    private static function insert_favorite($user_id, $item_id) {
        $database = parent::get_database();

        // The query to insert favorite record
        $query = "INSERT IGNORE INTO favorite (`user_id`, `item_id`) VALUES (:user_id, :item_id)";

        $insert = $database->prepare($query);
        $insert->execute(compact('user_id', 'item_id'));
    }


    /**
     * Model entry point
     */
    public static function run_task()
    {
        // Load engine from parent class
        parent::load_config();

        // Try to authenticate user
        $user_id = parent::authorize_user();

        // Get item id
        $item_id = parent::get_parameter('item_id', '^\d{1,11}$');

        // Check item_id parameter
        if ($item_id === false) {
            parent::show_error('Параметр item_id не соответствует условиям', 400);
        }

        // Good time to insert item_id to favorite table
        self::insert_favorite($user_id, $item_id);

        parent::show_success(true);
    }
}

