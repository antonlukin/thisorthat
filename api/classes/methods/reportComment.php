<?php
/**
 * Model for /reportComment API method
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License
 * @since       2.1
 */

namespace methods;


/**
 * Send report to item
 */
class reportComment extends \engine
{
    /**
     * Increment abuse value by comment id
     */
    private static function update_abuse($comment_id)
    {
        $database = parent::get_database();

        // The query to insert favorite record
        $query = "UPDATE comments SET abuse = abuse + 1 WHERE id = :comment_id";

        $insert = $database->prepare($query);
        $insert->execute(compact('comment_id'));
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

        // Get comment id
        $comment_id = parent::get_parameter('comment_id', '^\d{1,11}$');

        // Check comment_id parameter
        if ($comment_id === false) {
            parent::show_error('Параметр comment_id не соответствует условиям', 400);
        }

        // Good time to increment comment_id abuse value
        self::update_abuse($comment_id);

        parent::show_success(true);
    }
}

