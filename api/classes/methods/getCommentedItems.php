<?php
/**
 * Model for /getCommentedItems API method
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License
 * @since       2.0
 */

namespace methods;


/**
 * Get items for certian user
 */
class getCommentedItems extends \engine
{
    /**
     * Select votes from database by item id
     */
    private static function select_votes($item_id)
    {
        $database = self::get_database();

        // The query to get only certain user non-answered items from given section
        $query = "SELECT
            IFNULL(SUM(vote = 'first'), 0) first_vote,
            IFNULL(SUM(vote = 'last'), 0) last_vote
            FROM views WHERE item_id = :item_id GROUP BY item_id";

        $select = $database->prepare($query);
        $select->execute(compact('item_id'));

        return $select->fetch();
    }


    /**
     * Get items votes
     */
    private static function get_votes($items)
    {
        $redis = parent::get_redis();

        foreach ($items as &$item) {
            // Get votes from redis by id
            $votes = $redis->get(parent::$redis_prefix . $item['item_id']);

            if ($votes === false) {
                $votes = self::select_votes($item['item_id']);

                if ($votes === false) {
                    $votes = array_fill_keys(['first_vote', 'last_vote'], 0);
                }

                // Set votes to redis
                $redis->set(parent::$redis_prefix . $item['item_id'], array_map('intval', $votes));
            }

            $item = $item + $votes;
        }

        return $items;
    }


    /**
     * Get commented items by given user_id
     */
    private static function get_items($user_id, $limit, $offset)
    {
        $database = parent::get_database();

        // The query to get only certain user added items
        $query = "SELECT items.id AS item_id,
            ANY_VALUE(first_text) AS first_text,
            ANY_VALUE(last_text) AS last_text,
            ANY_VALUE(status) AS status,
            MAX(comments.id) AS comment_id
            FROM comments
            LEFT JOIN items ON comments.item_id = items.id
            WHERE comments.user_id = :user_id
            GROUP BY items.id
            ORDER BY comment_id DESC
            LIMIT :limit OFFSET :offset";

        $select = $database->prepare($query);
        $select->execute(compact('user_id', 'limit', 'offset'));

        return $select->fetchAll();
    }


    /**
     * Get total count of commented user items
     * For some reason it works faster than SQL_CALC_FOUND_ROWS
     */
    private static function calc_count($user_id)
    {
        $database = parent::get_database();

        // Get only count of user commented items
        $query = "SELECT COUNT(*) FROM (
            SELECT comments.item_id
            FROM comments
            LEFT JOIN  items ON comments.item_id = items.id
            WHERE comments.user_id = :user_id
            GROUP BY item_id
        ) AS items";

        $select = $database->prepare($query);
        $select->execute(compact('user_id'));

        return $select->fetchColumn();
    }


    /**
     * Get pages options
     */
    private static function get_pages($items, $user_id, $offset)
    {
        // Calc items count
        $count = count($items);

        // Get total count
        $total = self::calc_count($user_id);

        if ($total === false) {
            $total = 0;
        }

        $pages = compact('count', 'offset', 'total');

        return array_map('intval', $pages);
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
        // Numeric range 1-100 and 30 by default
        $limit = parent::get_parameter('limit', '^[1-9][0-9]?$|^100$', 30);

        // Get offset parameter
        $offset = parent::get_parameter('offset', '^[0-9]+$', 0);

        // Get items query
        $items = self::get_items($user_id, $limit, $offset);

        // Get items votes
        $items = self::get_votes($items);

        // Get pages options
        $pages = self::get_pages($items, $user_id, $offset);

        parent::show_success(compact('items', 'pages'));
    }
}

