<?php

namespace notify_events\modules\contact_form_7\tags;

use WPCF7_ContactForm;
use WPCF7_FormTag;

/**
 * Class ContactForm7
 * @package notify_events\modules\contact_form_7\tags
 */
class ContactForm7
{
    /**
     * @param string $tag_name
     * @return string
     */
    protected static function tag_name_to_label($tag_name)
    {
        $tag_name = explode('-', $tag_name);

        foreach ($tag_name as $idx => $slug) {
            $tag_name[$idx] = ucfirst($slug);
        }

        return implode(' ', $tag_name);
    }

    /**
     * @param WPCF7_ContactForm|false|null $form
     * @return string[]
     */
    public static function labels($form = null)
    {
        $labels = [
            'form-name'       => __('Form Name', WPNE),
            'form-all-fields' => __('Form All Fields', WPNE),
        ];

        if ($form) {
            /** @var WPCF7_FormTag $tag */
            foreach ($form->scan_form_tags() as $tag) {
                if (empty($tag->name)) {
                    continue;
                }

                $labels['field-' . $tag->name] = static::tag_name_to_label($tag->name);
            }
        }

        return $labels;
    }

    /**
     * @param WPCF7_ContactForm $form
     * @return string[]
     */
    public static function values($form)
    {
        $values = [
            'form-name' => $form->title(),
        ];

        $form_all_fields = [];

        /** @var WPCF7_FormTag $tag */
        foreach ($form->scan_form_tags() as $tag) {
            if (empty($tag->name)) {
                continue;
            }

            $values['field-' . $tag->name] = esc_html($_POST[$tag->name]);

            $form_all_fields[] = sprintf("<b>%s</b>:\n%s", esc_html(static::tag_name_to_label($tag->name)), esc_html($_POST[$tag->name]));
        }

        $values['form-all-fields'] = implode("\n\n", $form_all_fields);

        return $values;
    }

    /**
     * @param WPCF7_ContactForm $form
     * @return string[]
     */
    public static function preview($form)
    {
        if ($form) {
            $preview = [
                'form-name' => $form->title(),
            ];

            $form_all_fields = [];

            /** @var WPCF7_FormTag $tag */
            foreach ($form->scan_form_tags() as $tag) {
                if (empty($tag->name)) {
                    continue;
                }

                $preview['field-' . $tag->name] = esc_html(static::tag_name_to_label($tag->name));

                $form_all_fields[] = sprintf("<b>%s</b>:\n%s", esc_html(static::tag_name_to_label($tag->name)), esc_html(static::tag_name_to_label($tag->name) . ' Value'));
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