<?php

/**
 * @var View        $this
 * @var Event       $event
 * @var string|null $preview_subject
 * @var string|null $preview_message
 */

use notify_events\models\Event;
use notify_events\models\View;

add_thickbox();

?>

<style type="text/css">
    .wpne_form_tag_block {
        font-size: 0;
        margin-bottom: 5px;
    }
    .wpne_form_tag_block .wpne_tag {
        margin: 0 2px 2px 0;
        padding: 2px 5px;
        min-height: auto;
        line-height: inherit;
    }
</style>

<script type="text/javascript">
    (function($) {
        $(document).on('click', '.wpne_tag', function() {
            const tag_btn   = $(this);
            const tag_block = tag_btn.closest('.wpne_form_tag_block');

            const form_control = $('#' + tag_block.attr('for'));

            const tag = tag_btn.data('tag');

            let [start, end] = [form_control[0].selectionStart, form_control[0].selectionEnd];

            form_control[0].setRangeText('[' + tag + ']', start, end, 'select');

            start += tag.length + 2;

            form_control[0].focus();
            form_control[0].setSelectionRange(start, start);

            return false;
        });
    })(jQuery);
</script>

<?php if ($preview_subject) { ?>
    <div id="wpne-event-preview" style="display: none;">
        <p>
            <?= $preview_message ?>
        </p>
    </div>

    <script type="text/javascript">
        (function($) {
            $(window).load(function() {
                tb_show('<?= esc_attr($preview_subject) ?>', '/?TB_inline&width=300&height=150&inlineId=wpne-event-preview');
            });
        })(jQuery);
    </script>
<?php } ?>