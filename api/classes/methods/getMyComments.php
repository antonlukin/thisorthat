<?php
/**
 * Model for /getMyComments API method
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License
 * @since       2.0
 */

namespace methods;


/**
 * Get comments
 */
class getMyComments extends \engine
{
    /**
     * Get comments
     */
    private static function get_comments($user_id)
    {
        $database = parent::get_database();

        // The query to get item comments
        $query = "SELECT comments.id AS comment_id, parent, message, item_id
            FROM comments WHERE user_id = :user_id";

        $select = $database->prepare($query);
        $select->execute(compact('user_id'));

        return $select->fetchAll();
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

        // Get comments
        $comments = self::get_comments($user_id);

        parent::show_success(compact('comments'));
    }
}

