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
     * Get total count of user comments
     * For some reason it works faster than SQL_CALC_FOUND_ROWS
     */
    private static function calc_count($user_id)
    {
        $database = parent::get_database();

        // The query to get only count of comments by item
        $select = $database->prepare("SELECT COUNT(*) FROM comments WHERE user_id = :user_id");
        $select->execute(compact('user_id'));

        return $select->fetchColumn();
    }

    /**
     * Get pages options
     */
    private static function get_pages($comments, $user_id, $offset)
    {
        // Calc items count
        $count = count($comments);

        // Get total count
        $total = self::calc_count($user_id);

        if ($total === false) {
            $total = 0;
        }

        $pages = compact('count', 'offset', 'total');

        return array_map('intval', $pages);
    }


    /**
     * Add user avatars to comments
     */
    private static function add_avatar($comments, $user_id)
    {
        foreach ($comments as &$comment) {
            $comment['avatar'] = parent::$avatars . $user_id;
        }

        return $comments;
    }


    /**
     * Ge liset of comments by user id
     */
    private static function get_comments($user_id, $limit, $offset)
    {
        $database = parent::get_database();

        // The query to get item comments
        $query = "SELECT comments.id AS comment_id, parent, message, item_id
            FROM comments WHERE user_id = :user_id
            LIMIT :limit OFFSET :offset";

        $select = $database->prepare($query);
        $select->execute(compact('user_id', 'limit', 'offset'));

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

        // Get limit parameter
        // Numeric range 1-100 and 50 by default
        $limit = parent::get_parameter('limit', '^[1-9][0-9]?$|^100$', 50);

        // Get offset parameter
        $offset = parent::get_parameter('offset', '^[0-9]+$', 0);

        // Get comments
        $comments = self::get_comments($user_id, $limit, $offset);

        // Add avatar to comments
        $comments = self::add_avatar($comments, $user_id);

        // Get pages options
        $pages = self::get_pages($comments, $user_id, $offset);

        parent::show_success(compact('comments', 'pages'));
    }
}

