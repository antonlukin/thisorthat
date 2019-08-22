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
                $votes[$key] = (int) $votes[$key] + 1;
            }

            $redis->set(parent::$redis_prefix . $id , $votes);
        }
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
        $views = array_intersect($views, ['first', 'last', 'skip']);

        // Check array emptiness now
        if (count($views) === 0) {
            parent::show_error('Неверный формат массива с ответами', 400);
        }

        // Update votes in redis
        self::update_votes($views);

        // Add views to database
//        self::add_views($views, $user_id);

        parent::show_success(true);
    }
}

