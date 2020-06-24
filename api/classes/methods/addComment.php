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
    private static function insert_comment($user_id, $item_id, $message, $parent)
    {
        $database = parent::get_database();

        // The query to insert item comment
        $query = "INSERT INTO comments (user_id, item_id, message, parent)
            VALUES (:user_id, :item_id, :message, :parent)";

        $insert = $database->prepare($query);
        $insert->execute(compact('user_id', 'item_id', 'message', 'parent'));

        return $database->lastInsertId();
    }


    /**
     * Try to get user name
     */
    private static function generate_words($words = [])
    {
        $database = parent::get_database();

        // Get random adjective
        $query = $database->query("SELECT word FROM words WHERE pos = 'adjv' ORDER BY RAND() LIMIT 1");
        $words[] = mb_convert_case($query->fetchColumn(), MB_CASE_TITLE);

        // Get random noun
        $query = $database->query("SELECT word FROM words WHERE pos = 'noun' ORDER BY RAND() LIMIT 1");
        $words[] = $query->fetchColumn();

        return implode(' ', $words);
    }


    /**
     * Make unique username
     *
     * This function is not currently used because collisions are not critical
     */
    private static function make_username() {
        $database = parent::get_database();

        // Generate new name
        $name = self::generate_words();

        // The query to find user name by id
        $query = "SELECT id FROM users WHERE name = :name LIMIT 1";

        $select = $database->prepare($query);
        $select->execute(compact('name'));

        $user_id = $select->fetchColumn();

        // We hope that the recursion is finite
        if ($user_id !== false) {
            $name = self::make_username();
        }

        return $name;
    }


    /**
     * Add user name
     */
    private static function add_username($user_id)
    {
        $database = parent::get_database();

        // Make new username
        $name = self::generate_words();

        // The query to insert item comment
        $query = "UPDATE users SET name = :name WHERE id = :user_id";

        $update = $database->prepare($query);
        $update->execute(compact('name', 'user_id'));

        return $name;
    }


    /**
     * Try to get user name
     */
    private static function get_username($user_id)
    {
        $database = parent::get_database();

        // The query to find name by user id
        $query = "SELECT name FROM users WHERE id = :user_id";

        $select = $database->prepare($query);
        $select->execute(compact('user_id'));

        $name = $select->fetchColumn();

        // Add name if not exists
        if ($name === null) {
            $name = self::add_username($user_id);
        }

        return $name;
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
    private static function prepare_comment($user_id, $item_id, $message)
    {
        // Santinze comment message
        $message = parent::sanitize_text($message);

        // Check bad words
        $badwords = parent::has_badwords($message);

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

        return $message;
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

        // Get message parameter 1-300 characters
        $message = parent::get_parameter('message', '^.{1,300}$');

        // Check message parameter
        if ($message === false) {
            parent::show_error('Комментарий должен состоять из 1-300 символов', 400);
        }

        $message = self::prepare_comment($user_id, $item_id, $message);

        // We are able to insert comment now
        $comment_id = self::insert_comment($user_id, $item_id, $message, $parent);

        // Get this user name
        $name = self::get_username($user_id);

        // Get avatar with user id
        $avatar = parent::$avatars . $user_id;

        parent::show_success(compact('comment_id', 'user_id', 'parent', 'message', 'name', 'avatar'));
    }
}

