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

        for ($i = 0; $i < parent::$sections; $i++) {
            $limit = self::$limit - count($items);

            // The query to get only certain user non-answered items from given section
            $query = "SELECT items.id, items.first_text, items.last_text, items.status
                FROM items LEFT JOIN views
                ON (items.id = views.item_id AND views.user_id = :user_id)
                WHERE (views.id IS NULL) AND items.section = :section AND " . $condition . "
                LIMIT " . (int) $limit;

            $select = $database->prepare($query);
            $select->execute(compact('user_id', 'section', 'status'));

            $items = $items + $select->fetchAll(\PDO::FETCH_UNIQUE);

            // Break if already enough items
            if (count($items) >= self::$limit) {
                break;
            }

            $section = $section + 1;

            // Reset section value if too big
            if ($section > parent::$sections) {
                $section = 1;
            }
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
     * Model entry point
     */
    public static function run_task()
    {
        // Load engine from parent class
        parent::load_config();

        // Try to authenticate user
        $user_id = parent::authorize_user();

        // Get approve parameter
        $status = parent::get_parameter('status', '^(new|approved)$');

        // Get items query
        $items = self::get_items($user_id, $status);

        // Get items votes
        $items = self::get_votes($items);

        parent::show_success(compact('items'));
    }
}

