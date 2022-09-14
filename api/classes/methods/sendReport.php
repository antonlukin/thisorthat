<?php
/**
 * Model for /sendReport API method
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License
 * @since       2.0
 */

namespace methods;


/**
 * Send report to item
 */
class sendReport extends \engine
{
    /**
     * Insert report to database
     */
    private static function insert_report($user_id, $item_id, $reason)
    {
        $database = parent::get_database();

        // The query to insert favorite record
        $query = "INSERT IGNORE INTO reports (user_id, item_id, reason)
            VALUES (:user_id, :item_id, :reason)";

        $insert = $database->prepare($query);
        $insert->execute(compact('user_id', 'item_id', 'reason'));
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

        // Get reason parameter
        $reason = parent::get_parameter('reason', '^(typo|abuse|clone|dislike)$');

        if ($reason === false) {
            parent::show_error('Параметр reason не соответствует условиям', 400);
        }

        // Good time to insert item_id to report table
        self::insert_report($user_id, $item_id, $reason);

        parent::show_success(true);
    }
}

