<?php
/**
 * Model for /getItems API method
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License, https://github.com/antonlukin/thisorthat/blob/master/LICENSE
 * @since       2.0
 */

namespace methods;

/**
 *
 */
class getItems extends \engine
{
    public static function run()
    {
        self::load_config();

        // Get database instance
        $database = self::connect_database();
        echo '/getItems';
    }
}

