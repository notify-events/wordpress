<?php

namespace notify_events\modules\wpforms\models\events;

use ErrorException;
use notify_events\modules\wordpress\tags\Common;
use notify_events\modules\wpforms\models\Event;
use notify_events\modules\wpforms\tags\WPForms;
use WP_Post;

/**
 * Class FormSubmit
 * @package notify_events\modules\wpforms\models\events
 *
 * @property int    $id
 * @property string $title
 * @property int    $channel_id
 * @property int    $form_id
 */
class FormSubmit extends Event
{
    /**
     * @inheritDoc
     */
    public static function event_title()
    {
        return __('Form Submitted', WPNE);
    }

    public static function register()
    {
        add_action('wpforms_process_entry_save', static::class . '::handle', 10, 4);
    }

    /**
     * @param array $fields
     * @param       $entry
     * @param int   $form_id
     * @param array $form_data
     * @throws ErrorException
     */
    public static function handle($fields, $entry, $form_id, $form_data)
    {
        $tags = array_merge(
            WPForms::values($form_data['settings']['form_title'], $fields),
            Common::values()
        );

        static::execute($tags, [
            'meta_query' => [
                [
                    'key'   => '_wpne_form_id',
                    'value' => [0, $form_id],
                ],
            ],
        ]);
    }

    /**
     * @inheritDoc
     */
    public static function default_view()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public static function fields()
    {
        return array_merge(parent::fields(), [
            'form_id',
        ]);
    }

    /**
     * @inheritDoc
     */
    public static function rules()
    {
        return array_merge(parent::rules(), [
            'form_id' => [
                ['required'],
                ['int'],
            ],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function __construct($post = null)
    {
        $this->subject = __('Form "[form-name]" submitted', WPNE);
        $this->message = __("Form \"[form-name]\" submitted\n\n[form-all-fields]", WPNE);

        parent::__construct($post);
    }

    /**
     * @param WP_Post|null $form
     * @inheritDoc
     */
    public function tag_labels($form = null)
    {
        $form = $form !== null ? $form : $this->get_form();

        return array_merge([
            __('Form', WPNE) => WPForms::labels($form),
        ], parent::tag_labels());
    }

    /**
     * @inheritDoc
     */
    public function tag_preview()
    {
        return array_merge(
            WPForms::preview($this->get_form()),
            parent::tag_preview()
        );
    }

    /**
     * @return array
     */
    public static function form_list()
    {
        $result = [
            0 => __('All forms', WPNE),
        ];

        $args = [
            'orderby'       => 'ID',
            'order'         => 'DESC',
            'nopaging'      => true,
            'no_found_rows' => false,
            'post_status'   => 'publish',
        ];

        /** @var WP_Post[] $forms */
        $forms = wpforms()->form->get('', $args);

        foreach ($forms as $form) {
            $result[$form->ID] = $form->post_title;
        }

        return $result;
    }

    /**
     * @return array
     */
    public function form_tag_labels()
    {
        $result = [
            0 => $this->tag_labels(false),
        ];

        $args = [
            'orderby'       => 'ID',
            'order'         => 'DESC',
            'nopaging'      => true,
            'no_found_rows' => false,
            'post_status'   => 'publish',
        ];

        /** @var WP_Post[] $forms */
        $forms = wpforms()->form->get('', $args);

        foreach ($forms as $form) {
            $result[$form->ID] = $this->tag_labels($form);
        }

        return $result;
    }

    /**
     * @return WP_Post
     */
    public function get_form()
    {
        return wpforms()->form->get($this->form_id);
    }
}
