<?php
/**
 * Model for /getMyItems API method
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License
 * @since       2.0
 */

namespace methods;


/**
 * Get certain user own items
 */
class getMyItems extends \engine
{
    /**
     * Select votes from database by item id
     */
    private static function select_votes($item_id)
    {
        $database = self::get_database();

        // The query to get only certain user non-answered items from given section
        $query = "SELECT
            IFNULL(SUM(vote = 'left'), 0) first_vote,
            IFNULL(SUM(vote = 'right'), 0) last_vote
            FROM views WHERE item_id = :item_id GROUP BY item_id";

        $select = $database->prepare($query);
        $select->execute(compact('item_id'));

        $votes = $select->fetch();

        if ($votes === false) {
            $votes = array_fill_keys(['first_vote', 'last_vote'], 0);
        }

        return $votes;
    }


    /**
     * Get items votes
     */
    private static function get_votes($items)
    {
        foreach ($items as $id => &$item) {
            $redis = parent::get_redis();

            // Get votes from redis by id
            $votes = $redis->get(parent::$redis_prefix . $id);

            if ($votes === false) {
                $votes = self::select_votes($id);
                $redis->set(parent::$redis_prefix . $id, $votes);
            }

            $item = $item + $votes;
        }

        return $items;
    }


    /**
     * Get availble items
     */
    private static function get_items($user_id, $limit, $offset)
    {
        $database = parent::get_database();

        // The query to get only certain user added items
        $query = "SELECT id, first_text, last_text, status, reason
            FROM items WHERE user_id = :user_id
            LIMIT :limit OFFSET :offset";

        $select = $database->prepare($query);
        $select->execute(compact('user_id', 'limit', 'offset'));

        $items = $select->fetchAll(\PDO::FETCH_UNIQUE);

        // Get items votes
        $items = self::get_votes($items);

        // Remove reason field from unrejected items
        foreach ($items as $id => &$item) {
            if ($item['status'] !== 'rejected') {
                unset($item['reason']);
            }
        }

        return $items;
    }


    /**
     * Get total count of user items
     * For some reason it works faster than SQL_CALC_FOUND_ROWS
     */
    private static function calc_count($user_id)
    {
        $database = parent::get_database();

        // Get only count of user items
        $select = $database->prepare("SELECT COUNT(*) FROM items WHERE user_id = :user_id");
        $select->execute(compact('user_id'));

        return (int) $select->fetchColumn();
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

        // Get total count
        $total = self::calc_count($user_id);

        parent::show_success(compact('items', 'total'));
    }
}

