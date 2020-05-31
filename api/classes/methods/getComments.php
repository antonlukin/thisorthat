<?php
/**
 * Model for /getComments API method
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License
 * @since       2.0
 */

namespace methods;


/**
 * Get comments by item
 */
class getComments extends \engine
{
    /**
     * Get comments
     */
    private static function get_comments($item_id, $limit, $offset)
    {
        $database = parent::get_database();

        // The query to get item comments
        $query = "SELECT comments.id AS comment_id, user_id, parent, message, name
            FROM comments, users WHERE item_id = :item_id AND user_id = users.id
            LIMIT :limit OFFSET :offset";

        $select = $database->prepare($query);
        $select->execute(compact('item_id', 'limit', 'offset'));

        return $select->fetchAll();
    }


    /**
     * Get total count of item comments
     * For some reason it works faster than SQL_CALC_FOUND_ROWS
     */
    private static function calc_count($item_id)
    {
        $database = parent::get_database();

        // The query to get only count of comments by item
        $select = $database->prepare("SELECT COUNT(*) FROM comments WHERE item_id = :item_id");
        $select->execute(compact('item_id'));

        return $select->fetchColumn();
    }


    /**
     * Get pages options
     */
    private static function get_pages($comments, $item_id, $offset)
    {
        // Calc items count
        $count = count($comments);

        // Get total count
        $total = self::calc_count($item_id);

        if ($total === false) {
            $total = 0;
        }

        $pages = compact('count', 'offset', 'total');

        return array_map('intval', $pages);
    }


    /**
     * Add user avatars to comments
     */
    private static function add_avatar($comments)
    {
        foreach ($comments as &$comment) {
            $comment['avatar'] = parent::$avatars . $comment['user_id'];
        }

        return $comments;
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

        // Get limit parameter
        // Numeric range 1-100 and 50 by default
        $limit = parent::get_parameter('limit', '^[1-9][0-9]?$|^100$', 50);

        // Get offset parameter
        $offset = parent::get_parameter('offset', '^[0-9]+$', 0);

        // Get comments
        $comments = self::get_comments($item_id, $limit, $offset);

        // Add avatar to comments
        $comments = self::add_avatar($comments);

        // Get pages options
        $pages = self::get_pages($comments, $item_id, $offset);

        parent::show_success(compact('comments', 'pages'));
    }
}

