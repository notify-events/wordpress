<?php


namespace notify_events\models;

use WP_Post;
use WP_Query;

/**
 * Class PostModel
 * @package notify_events\models
 *
 * @property int    $id
 * @property string $title
 */
abstract class PostModel extends Model implements PostModelInterface
{
    /**
     *
     */
    public static function register_post_type()
    {
        register_post_type(static::post_type(), [
            'labels' => [
                'name' => static::post_type(),
                'singular_name' => static::post_type(),
            ],
            'rewrite' => false,
            'query_var' => false,
            'public' => false,
            'capability_type' => WPNE,
        ]);
    }

    /**
     * @return array
     */
    public static function fields()
    {
        return [
            'id',
            'title',
        ];
    }

    /**
     * @return array
     */
    public static function rules()
    {
        return [
            'title' => [
                ['required'],
                ['strip_tags'],
                ['trim'],
                ['string', 'max' => 200],
            ],
        ];
    }

    /**
     * @param WP_Post|null $post
     * @return static
     */
    protected static function instantiate($post)
    {
        return new static($post);
    }

    /**
     * @param string $args
     * @return static[]
     */
    public static function find($args = '')
    {
        $defaults = [
            'post_status'    => 'any',
            'posts_per_page' => -1,
            'offset'         => 0,
            'orderby'        => 'ID',
            'order'          => 'ASC',
        ];

        $args = wp_parse_args($args, $defaults);
        $args['post_type'] = static::post_type();

        $query = new WP_Query();
        $posts = $query->query($args);

        $models = [];

        foreach ((array)$posts as $post) {
            $models[] = static::instantiate($post);
        }

        return $models;
    }

    /**
     * @param int  $id
     * @param bool $throw
     * @return static|null
     */
    public static function findOne($id, $throw = true)
    {
        $post = get_post(absint($id));

        if ($post === null) {
            if ($throw) {
                wp_die(sprintf('Channel #%s not found!', esc_html($id)));
            } else {
                return null;
            }
        }

        return static::instantiate($post);
    }

    /**
     * @return array
     */
    public static function field_assign()
    {
        return [
            'id'    => 'ID',
            'title' => 'post_title',
        ];
    }

    /**
     * Post constructor.
     * @param WP_Post|null $post
     */
    public function __construct($post = null)
    {
        $data = [];

        if ($post) {
            $assign = static::field_assign();
            $fields = static::fields();

            $meta = get_post_meta($post->ID);

            foreach ($fields as $field) {
                if (array_key_exists($field, $assign)) {
                    $post_field = $assign[$field];

                    $data[$field] = $post->$post_field;
                } elseif (array_key_exists('_wpne_' . $field, $meta)) {
                    $value = $meta['_wpne_' . $field][0];
                    $value = maybe_unserialize($value);

                    $data[$field] = $value;
                }
            }
        }

        parent::__construct($data);
    }

    /**
     * @return bool
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $post = [];
        $meta = [];

        $assign = $this->field_assign();
        $fields = $this->fields();

        foreach ($fields as $field) {
            if (($field == 'id') && !$this->id) {
                continue;
            }

            if (array_key_exists($field, $assign)) {
                $post[$assign[$field]] = $this->$field;
            } else {
                $meta[] = $field;
            }
        }

        $post = array_merge($post, [
            'post_type'   => static::post_type(),
            'post_status' => 'published',
        ]);

        if (!$this->id) {
            $id = wp_insert_post($post);
        } else {
            $id = wp_update_post($post);
        }

        if (!$id) {
            return false;
        }

        $this->id = $id;

        foreach ($meta as $field) {
            update_post_meta($this->id, '_wpne_' . $field, wp_slash($this->$field));
        }

        return true;
    }

    /**
     * @return bool
     */
    public function delete()
    {
        if (!$this->id) {
            return true;
        }

        if (!wp_delete_post($this->id, true)) {
            return false;
        }

        $this->id = null;

        return true;
    }
}