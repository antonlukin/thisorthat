<?php
/**
 * Model for /addComment API method
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License
 * @since       2.0
 */

namespace methods;


/**
 * Add comment to item
 */
class addComment extends \engine
{
    /**
     * Insert comment
     */
    private static function insert_comment($user_id, $item_id, $text, $parent)
    {
        $database = parent::get_database();

        // The query to insert item comment
        $query = "INSERT INTO comments (user_id, item_id, text, parent)
            VALUES (:user_id, :item_id, :text, :parent)";

        $insert = $database->prepare($query);
        $insert->execute(compact('user_id', 'item_id', 'text', 'parent'));

        return $database->lastInsertId();
    }


    /**
     * Check comments flood
     */
    private static function check_flood($user_id)
    {
        $database = parent::get_database();

        // The query to find last user comment date
        $query = "SELECT * FROM comments
            WHERE user_id = :user_id AND created >= NOW() - INTERVAL 20 SECOND
            LIMIT 1";

        $select = $database->prepare($query);
        $select->execute(compact('user_id'));

        return $select->fetchColumn();
    }


    /**
     * Prepare comment before inserting to database
     */
    private static function prepare_comment($user_id, $item_id, $text)
    {
        // Santinze comment text
        $text = parent::sanitize_text($text);

        // Check bad words
        $badwords = parent::has_badwords($text);

        // Stop working on bad words
        if ($badwords === true) {
            parent::show_error('Комментарий содержит нецензурную лексику', 400);
        }

        // Find last user comment sent within 10s
        $flood = self::check_flood($user_id);

        // Check if too fast commenting
        if ($flood !== false) {
            parent::show_error('Нельзя отправлять комментарии так часто', 400);
        }

        return $text;
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

        // Get parent parameter
        $parent = parent::get_parameter('parent', '^\d{1,11}$', 0);

        // Get text parameter 1-300 characters
        $text = parent::get_parameter('text', '^.{1,300}$');

        // Check text parameter
        if ($text === false) {
            parent::show_error('Комментарий должен состоять из 1-300 символов', 400);
        }

        $text = self::prepare_comment($user_id, $item_id, $text);

        // We are able to insert comment now
        $comment_id = self::insert_comment($user_id, $item_id, $text, $parent);

        parent::show_success(compact('comment_id'));
    }
}

