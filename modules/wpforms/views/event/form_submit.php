<?php

/**
 * @var View       $this
 * @var FormSubmit $event
 * @var string     $preview_subject
 * @var string     $preview_message
 */

use notify_events\models\View;
use notify_events\modules\wpforms\models\events\FormSubmit;

?>

<div class="wrap" id="notify-events-wpforms-event-form-submit">

    <h2>
        <?= esc_html($event->title) ?>
    </h2>

    <script type="text/javascript">
        (function($) {
            $(document).ready(function() {
                const form_tag_list  = <?= json_encode($event->form_tag_labels(), 320) ?>;
                const $form_select    = $('#wpne_form_id');
                const $form_tag_block = $('.wpne_form_tag_block');

                function change_form() {
                    const form_id = $form_select.val();

                    $form_tag_block.each(function() {
                        const $block = $(this);

                        $block.empty();

                        if (form_id in form_tag_list) {
                            console.log(form_tag_list[form_id]);

                            const $nav = $('<div>')
                                .addClass('nav-tab-wrapper wpne-tabs')
                                .attr('for', '#' + $block.attr('for') + '-tabs')
                                .appendTo($block);

                            $.each(form_tag_list[form_id], function(group) {
                                $('<a>')
                                    .attr('href', '#')
                                    .addClass('nav-tab')
                                    .text(group)
                                    .appendTo($nav);
                            });

                            const $content = $('<div>')
                                .attr('id', $block.attr('for') + '-tabs')
                                .addClass('wpne-tabs-content')
                                .appendTo($block);

                            $.each(form_tag_list[form_id], function(group, tags) {
                                const $row = $('<div>')
                                    .appendTo($content);

                                $.each(tags, function(tag, label) {
                                    $('<button>')
                                        .html('<b>' + tag + '</b>' + (label !== false ? ' | <small>' + label + '</small>' : ''))
                                        .data('tag', tag)
                                        .addClass('wpne_tag button')
                                        .appendTo($row);
                                });
                            });

                            $nav.wpne_tabs();
                        }
                    });
                }

                $form_select.change(change_form);

                change_form();
            });
        })(jQuery);
    </script>

    <form method="post">
        <?= $this->render('_header', [
            'event'           => $event,
            'preview_subject' => $preview_subject,
            'preview_message' => $preview_message,
        ], false) ?>

        <table class="form-table">
            <?= $this->render('form/title', [
                'event' => $event,
            ], false) ?>
            <?= $this->render('form/enabled', [
                'event' => $event,
            ], false) ?>
            <?= $this->render('form/channel', [
                'event' => $event,
            ], false) ?>
            <?= $this->render('form/_select', [
                'model' => $event,
                'title' => __('Form', WPNE),
                'field' => 'form_id',
                'items' => $event::form_list(),
            ], false) ?>
            <?= $this->render('form/subject', [
                'event' => $event,
            ], false) ?>
            <?= $this->render('form/message', [
                'event' => $event,
            ], false) ?>
            <?= $this->render('form/priority', [
                'event' => $event,
            ], false) ?>
        </table>

        <p class="submit">
            <input type="submit" class="button-primary" value="<?= __('Save Changes', WPNE) ?>">
            <input type="submit" class="button" name="preview" value="<?= __('Preview', WPNE) ?>">
        </p>
    </form>

</div>
