<?php
/**
 * Model for /getItems API method
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License
 * @since       2.0
 */

namespace methods;


/**
 * Get items for certian user
 */
class getItems extends \engine
{
    /**
     * Get items limit
     */
    private static $limit = 30;


    /**
     * Get user section
     */
    private static function get_section($user_id)
    {
        $database = self::get_database();

        // Try to get current user section
        $select = $database->prepare("SELECT section FROM users WHERE id = :user_id LIMIT 1");
        $select->execute(compact('user_id'));

        return $select->fetchColumn();
    }


    /**
     * Replace user section
     */
    private static function set_section($user_id, $section)
    {
        $database = self::get_database();

        // Try to update current user section
        $query = $database->prepare("UPDATE users SET section = :section WHERE id = :user_id");
        $query->execute(compact('user_id', 'section'));
    }

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
     * Get items according user section
     */
    private static function get_items($user_id, $status, $items = [])
    {
        $database = parent::get_database();

        // Show all except rejected items by default
        $condition = 'items.status = :status';

        if ($status === false) {
            $condition = 'items.status <> :status';

            // Set status if notset
            $status = 'rejected';
        }

        // Get required user section
        $section = self::get_section($user_id);

        if ($section === false) {
            $section = 1;
        }

        $limit = self::$limit - count($items);

        // The query to get only certain user non-answered items from given section
        $query = "SELECT items.id AS item_id, ANY_VALUE(items.first_text) AS first_text,
            ANY_VALUE(items.last_text) AS last_text, ANY_VALUE(items.status) AS status,
            IF(COUNT(comments.id) < 1, NULL, 'available') AS comments
            FROM items LEFT JOIN views
            ON (items.id = views.item_id AND views.user_id = :user_id)
            LEFT JOIN comments
            ON (items.id = comments.item_id)
            WHERE (views.id IS NULL) AND items.section = :section AND {$condition}
            GROUP BY items.id
            LIMIT {$limit}";

        $select = $database->prepare($query);
        $select->execute(compact('user_id', 'section', 'status'));

        $items = $items + $select->fetchAll();

        // Break if already enough items
        if (count($items) >= self::$limit) {
            return $items;
        }

        $section = intval($section) + 1;

        // Reset section value if too big
        if ($section > parent::$sections) {
            $section = 1;
        }

        // We should replace user section now
        self::set_section($user_id, $section);

        return $items;
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
     * Model entry point
     */
    public static function run_task()
    {
        // Load engine from parent class
        parent::load_config();

        // Try to authenticate user
        $user_id = parent::authorize_user();

        // Get status parameter
        $status = parent::get_parameter('status', '^(new|approved)$');

        // Get items query
        $items = self::get_items($user_id, $status);

        // Get items votes
        $items = self::get_votes($items);

        parent::show_success(compact('items'));
    }
}

