<?php

namespace notify_events\modules\ninja_forms\tags;

use NF_Database_Models_Field;
use NF_Database_Models_Form;

/**
 * Class NinjaForms
 *
 * @package notify_events\modules\ninja_forms\tags
 */
class NinjaForms
{
    /**
     * @param NF_Database_Models_Form|null $form
     * @return array
     */
    public static function labels($form)
    {
        $labels = [
            'form-name'       => __('Form Name', WPNE),
            'form-all-fields' => __('Form All Fields', WPNE),
        ];

        if ($form) {
            /** @var NF_Database_Models_Field[] $fields */
            $fields = Ninja_Forms()->form($form->get_id())->get_fields();

            foreach ($fields as $field) {
                if ($field->get_setting('type') == 'submit') {
                    continue;
                }

                $labels['field-' . $field->get_setting('key')] = $field->get_setting('label');
            }
        }

        return $labels;
    }

    /**
     * @param array $form_data
     * @return array
     */
    public static function values($form_data)
    {
        $form = Ninja_Forms()->form($form_data['id']);

        $values = [
            'form-name' => $form_data['settings']['title'],
        ];

        $form_all_fields = [];

        foreach ($form_data['fields'] as $field) {
            $field_model = $form->get_field($field['id']);

            if ($field_model->get_setting('type') == 'submit') {
                continue;
            }

            $values['field-' . $field['key']] = esc_html($field['value']);

            $form_all_fields[] = sprintf("<b>%s</b>:\n%s", esc_html($field_model->get_setting('label')), esc_html($field['value']));
        }

        $values['form-all-fields'] = implode("\n\n", $form_all_fields);

        return $values;
    }

    /**
     * @param NF_Database_Models_Form|null $form
     * @return array
     */
    public static function preview($form)
    {
        $form_all_fields = [];

        if ($form) {
            $preview = [
                'form-name' => 'Sample form',
            ];

            /** @var NF_Database_Models_Field[] $fields */
            $fields = Ninja_Forms()->form($form->get_id())->get_fields();

            foreach ($fields as $field) {
                if ($field->get_setting('type') == 'submit') {
                    continue;
                }

                $preview['field-' . $field->get_setting('key')] = esc_html($field->get_setting('label') . ' Value');
            }
        } else {
            $preview = [
                'form-name' => 'Sample form',
            ];
        }

        $preview['form-all-fields'] = implode("\n\n", $form_all_fields);

        return $preview;
    }
}