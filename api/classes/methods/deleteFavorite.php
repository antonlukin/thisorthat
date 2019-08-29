<?php
/**
 * Model for /deleteFavorite API method
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License
 * @since       2.0
 */

namespace methods;


/**
 * Add item to user favorite list
 */
class deleteFavorite extends \engine
{
    /**
     * Delete item from favoite tabe
     */
    private static function delete_favorite($user_id, $item_id) {
        $database = parent::get_database();

        // The query to delete favorite record
        $query = "DELETE IGNORE FROM favorite WHERE user_id = :user_id AND item_id = :item_id";

        $delete = $database->prepare($query);
        $delete->execute(compact('user_id', 'item_id'));
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

        // Good time to delete item_id from favorite table
        self::delete_favorite($user_id, $item_id);

        parent::show_success(true);
    }
}

