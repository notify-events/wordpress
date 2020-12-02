<?php

namespace notify_events\modules\wpforms\tags;

use WP_Post;

/**
 * Class WPForms
 *
 * @package notify_events\modules\wpforms\tags
 */
class WPForms
{
    /**
     * @param WP_Post|null $form
     * @return array
     */
    public static function labels($form = null)
    {
        $labels = [
            'form-name'       => __('Form Name', WPNE),
            'form-all-fields' => __('Form All Fields', WPNE),
        ];

        if ($form) {
            $form_data = json_decode($form->post_content, true);

            foreach ((array)$form_data['fields'] as $field) {
                $labels['field-' . $field['id']] = $field['label'];
            }
        }

        return $labels;
    }

    /**
     * @param string $title
     * @param array  $fields
     * @return string[]
     */
    public static function values($title, $fields)
    {
        $values = [
            'form-name' => $title,
        ];

        $form_all_fields = [];

        foreach ($fields as $field) {
            $values['field-' . $field['id']] = esc_html($field['value']);

            $form_all_fields[] = sprintf("<b>%s</b>:\n%s", esc_html($field['name']), esc_html($field['value']));
        }

        $values['form-all-fields'] = implode("\n\n", $form_all_fields);

        return $values;
    }

    /**
     * @param WP_Post $form
     * @return array
     */
    public static function preview($form)
    {
        if ($form) {
            $preview = [
                'form-name' => $form->post_title,
            ];

            $form_all_fields = [];

            $form_data = json_decode($form->post_content, true);

            foreach ($form_data['fields'] as $field) {
                $preview['field-' . $field['id']] = esc_html($field['label']);

                $form_all_fields[] = sprintf("<b>%s</b>:\n%s", esc_html($field['label']), esc_html($field['label'] . ' Value'));
            }

            $preview['form-all-fields'] = implode("\n\n", $form_all_fields);
        } else {
            $preview = [
                'form-name'       => __('Contact Form', WPNE),
                'form-all-fields' => __("<b>Example Field:</b>\nExample Field Value", WPNE),
            ];
        }

        return $preview;
    }
}