<?php

namespace notify_events\modules\contact_form_7\models\events;

use ErrorException;
use notify_events\modules\contact_form_7\models\Event;
use notify_events\modules\contact_form_7\tags\ContactForm7;
use notify_events\modules\wordpress\tags\Common;
use WPCF7_ContactForm;

/**
 * Class FormSubmit
 * @package notify_events\modules\contact_form_7\models\events
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
        return __('Form Submit', WPNE);
    }

    public static function register()
    {
        add_action('wpcf7_before_send_mail', static::class . '::handle', 10, 1);
    }

    /**
     * @param WPCF7_ContactForm $form
     * @throws ErrorException
     */
    public static function handle($form)
    {
        $tags = array_merge(
            ContactForm7::values($form),
            Common::values()
        );

        static::execute($tags, [
            'meta_query' => [
                [
                    'key'   => '_wpne_form_id',
                    'value' => [0, $form->id()],
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
        $this->subject = __('Form "[form-name]" submit', WPNE);
        $this->message = __("Form \"[form-name]\" submit\n\n[form-all-fields]", WPNE);

        parent::__construct($post);
    }

    /**
     * @param WPCF7_ContactForm|false|null $form
     * @inheritDoc
     */
    public function tag_labels($form = null)
    {
        $form = $form !== null ? $form : $this->get_form();

        return array_merge([
            __('Form', WPNE) => ContactForm7::labels($form),
        ], parent::tag_labels());
    }

    /**
     * @inheritDoc
     */
    public function tag_preview()
    {
        return array_merge(
            ContactForm7::preview($this->get_form()),
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

        $forms = WPCF7_ContactForm::find([
            'orderby' => 'title',
            'order'   => 'ASC',
        ]);

        /** @var WPCF7_ContactForm $form */
        foreach ($forms as $form) {
            $result[$form->id()] = $form->title();
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

        $forms = WPCF7_ContactForm::find([
            'orderby' => 'title',
            'order'   => 'ASC',
        ]);

        /** @var WPCF7_ContactForm $form */
        foreach ($forms as $form) {
            $result[$form->id()] = $this->tag_labels($form);
        }

        return $result;
    }

    /**
     * @return WPCF7_ContactForm
     */
    public function get_form()
    {
        return WPCF7_ContactForm::get_instance($this->form_id);
    }
}