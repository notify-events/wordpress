<?php

namespace notify_events\modules\ninja_forms\models\events;

use ErrorException;
use NF_Database_FormsController;
use NF_Database_Models_Form;
use notify_events\modules\ninja_forms\models\Event;
use notify_events\modules\ninja_forms\tags\NinjaForms;
use notify_events\modules\wordpress\tags\Common;

/**
 * Class FormSubmit
 * @package notify_events\modules\ninja_forms\models\events
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
        add_filter('ninja_forms_submit_data', static::class . '::handle');
    }

    /**
     * @param array $form_data
     * @return array
     * @throws ErrorException
     */
    public static function handle($form_data)
    {
        $tags = array_merge(
            NinjaForms::values($form_data),
            Common::values()
        );

        static::execute($tags, [
            'meta_query' => [
                [
                    'key'   => '_wpne_form_id',
                    'value' => [0, $form_data['id']],
                ],
            ],
        ]);

        return $form_data;
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
     * @param NF_Database_Models_Form|null $form
     * @inheritDoc
     */
    public function tag_labels($form = null)
    {
        $form = $form !== null ? $form : $this->get_form();

        return array_merge([
            __('Form', WPNE) => NinjaForms::labels($form),
        ], parent::tag_labels());
    }

    /**
     * @inheritDoc
     */
    public function tag_preview()
    {
        return array_merge(
            NinjaForms::preview($this->get_form()),
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

        $db_forms_controller = new NF_Database_FormsController();
        $forms = $db_forms_controller->getFormsData();

        foreach ($forms as $form) {
            $result[$form->id] = $form->title;
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

        $db_forms_controller = new NF_Database_FormsController();
        $forms = $db_forms_controller->getFormsData();

        foreach ($forms as $form) {
            $form = Ninja_Forms()->form($form->id)->get();

            $result[$form->get_id()] = $this->tag_labels($form);
        }

        return $result;
    }

    /**
     * @return NF_Database_Models_Form
     */
    public function get_form()
    {
        return Ninja_Forms()->form($this->form_id)->get();
    }
}
