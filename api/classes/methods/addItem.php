<?php
/**
 * Model for /addItem API method
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License
 * @since       2.0
 */

namespace methods;


/**
 * Get certain user own items
 */
class addItem extends \engine
{
    /**
     * Model entry point
     */
    public static function run_task()
    {
        // Load engine from parent class
        parent::load_config();

        // Try to authenticate user
        $user_id = parent::authorize_user();

        // Get first_text parameter
        // Any symbols min length 4 char
        $first_text = parent::get_parameter('first_text', '^.{4,150}$');

        // Check first_text parameter
        if ($first_text === false) {
            parent::show_error('Parameter first_text mismatched', 400);
        }

        // Get last_text parameter
        // Any symbols min length 4 char
        $last_text = parent::get_parameter('last_text', '^.{4,150}$');

        // Check first_text parameter
        if ($last_text === false) {
            parent::show_error('Parameter last_text mismatched', 400);
        }
    }
}

