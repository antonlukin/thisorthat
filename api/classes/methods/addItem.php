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
 * Add new item with first and last texts
 */
class addItem extends \engine
{
    /**
     * Get last item section field
     */
    private static function get_section()
    {
        $database = parent::get_database();

        // Try to get last item section field
        $select = $database->query("SELECT section FROM items ORDER BY id DESC LIMIT 1");

        return $select->fetchColumn();
    }


    /**
     * Find clone questions
     */
    private static function search_clone($question)
    {
        $database = parent::get_database();

        // The query to find duplicate item
        $query = "SELECT id FROM items
            WHERE (first_text = ? AND last_text = ?)
            OR (last_text = ? AND first_text = ?)
            AND status <> 'rejected' ORDER BY id DESC LIMIT 1";

        $select = $database->prepare($query);
        $select->execute(array_merge($question, $question));

        return $select->fetchColumn();
    }


    /**
     * Insert item to database
     */
    private static function insert_item($user_id, $first_text, $last_text) {
        $database = parent::get_database();

        // Get last item section field
        $section = self::get_section();

        // Reset section value if too big
        if ($section === false || $section > parent::$sections) {
            $section = 0;
        }

        $section = intval($section) + 1;

        // The query to insert item object
        $query = "INSERT INTO items (user_id, first_text, last_text, section)
            VALUES (:user_id, :first_text, :last_text, :section)";

        $insert = $database->prepare($query);
        $insert->execute(compact('user_id', 'first_text', 'last_text', 'section'));

        return $database->lastInsertId();
    }


    /**
     * Prepare item before inserting to database
     */
    private static function prepare_item($user_id, $question)
    {
        // Santinze question texts
        foreach ($question as &$text) {
            $text = parent::sanitize_text($text);
        }

        // Check bad words
        $badwords = parent::has_badwords(join(' ', $question));

        // Stop working on bad words
        if ($badwords === true) {
            parent::show_error('Текст вопроса содержит нецензурную лексику', 400);
        }

        $clone_id = self::search_clone($question);

        // Stop if clone exists
        if ($clone_id !== false) {
            parent::show_error('В нашей базе уже есть такой вопрос', 400, compact('clone_id'));
        }

        return $question;
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

        // Get first_text parameter 4-150 characters
        $first_text = parent::get_parameter('first_text', '^.{4,150}$');

        // Check first_text parameter
        if ($first_text === false) {
            parent::show_error('Вопрос должен состоять из 4-150 символов', 400);
        }

        // Get last_text parameter 4-150 characters
        $last_text = parent::get_parameter('last_text', '^.{4,150}$');

        // Check first_text parameter
        if ($last_text === false) {
            parent::show_error('Вопрос должен состоять из 4-150 символов', 400);
        }

        // Check text params for indentity
        if ($last_text === $first_text) {
            parent::show_error('Вопросы не могут быть одинаковыми', 400);
        }

        $question = self::prepare_item($user_id, [$first_text, $last_text]);

        // Parse first and last texts from question
        list($first_text, $last_text) = $question;

        // We are able to insert item now
        $item_id = self::insert_item($user_id, $first_text, $last_text);

        parent::show_success(compact('item_id'));
    }
}

