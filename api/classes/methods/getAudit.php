<?php
/**
 * Model for /getAudit API method
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License
 * @since       2.0
 */

namespace methods;


/**
 * Get only unaudited items for certain user
 */
class getAudit extends \engine
{
    /**
     * Get items limit
     */
    private static $limit = 50;


    /**
     * Get unaudited items for certain user
     */
    private static function get_items($user_id, $items = [])
    {
        $database = parent::get_database();

        // The query to get only certain user non-answered items from given section
        $query = "SELECT items.id AS item_id, items.first_text, items.last_text
            FROM items LEFT JOIN audit
            ON (items.id = audit.item_id AND audit.user_id = :user_id)
            WHERE (audit.id IS NULL) AND status = 'new'
            LIMIT " . self::$limit;

        $select = $database->prepare($query);
        $select->execute(compact('user_id'));

        $items = $select->fetchAll();

        shuffle($items);

        return array_slice($items, 0, self::$limit);
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

        // Get items query
        $items = self::get_items($user_id);

        parent::show_success(compact('items'));
    }
}

