<?php
/**
 * Model for /setViewed API method
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License
 * @since       2.0
 */

namespace methods;


/**
 * Get certain user own items
 */
class setViewed extends \engine
{
    /**
     * Filter only valid views from response
     */
    private static function sanitize_views($views)
    {
        foreach ($views as $id => $vote) {
            if (is_int($id) && in_array($vote, ['first', 'last', 'skip'])) {
                continue;
            }

            unset($views[$id]);
        }

        return $views;
    }


    /**
     * Update items votes in redis
     */
    private static function update_votes($views)
    {
        $redis = parent::get_redis();

        // Remove 'skip' vote here
        $views = array_intersect($views, ['first', 'last']);

        foreach ($views as $id => $vote) {
            $key = $vote . '_vote';

            // Get votes by item id
            $votes = $redis->get(parent::$redis_prefix . $id);

            // Update only if already set
            if (isset($votes[$key])) {
                $votes[$key] = $votes[$key] + 1;

                // Set votes to redis
                $redis->set(parent::$redis_prefix . $id , array_map('intval', $votes));
            }
        }
    }


    /**
     * Add views to database
     */
    private static function insert_views($user_id, $views)
    {
        $database = parent::get_database();

        // The query for inserting values
        $query = "INSERT IGNORE INTO views (user_id, item_id, vote) VALUES (:user_id, :item_id, :vote)";

        $database->beginTransaction();

        try {
            $insert = $database->prepare($query);

            foreach ($views as $item_id => $vote) {
                $insert->execute(compact('user_id', 'item_id', 'vote'));
            }

        } catch(\PDOException $e) {
            $database->rollBack();

            throw $e;
        }

        $database->commit();
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

        // Get views array
        $views = parent::get_array('views');

        // Filter only valid views
        $views = self::sanitize_views($views);

        // Check array emptiness now
        if (count($views) < 1) {
            parent::show_error('Неверный формат массива с ответами', 400);
        }

        // Check array max length
        if (count($views) > 1000) {
            parent::show_error('Массив с ответами слишком большой', 413);
        }

        // Update votes in redis
        self::update_votes($views);

        // Add views to database
        self::insert_views($user_id, $views);

        parent::show_success(true);
    }
}

