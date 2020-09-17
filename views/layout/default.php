<?php

/**
 * @var View       $this
 * @var Controller $controller
 * @var string     $content
 */

use notify_events\controllers\AboutController;
use notify_events\controllers\ChannelController;
use notify_events\controllers\EventController;
use notify_events\controllers\TestController;
use notify_events\helpers\Html;
use notify_events\models\Alert;
use notify_events\models\Controller;
use notify_events\models\View;

add_thickbox();

?>

<div class="wrap">
    <style type="text/css">
        .wpne-form-group.wpne-has-error input,
        .wpne-form-group.wpne-has-error textarea,
        .wpne-form-group.wpne-has-error select {
            border-color: #dc3232;
            border-width: 2px;
        }
        .wpne-form-group.wpne-has-error th[scope=row] {
            color: #dc3232;
        }
        .wpne-form-group .wpne-error {
            display: none;
            color: #dc3232;
            font-weight: 600;
        }
        .wpne-form-group.wpne-has-error .wpne-error {
            display: block;
        }
        .wpne-badge {
            padding: 1px 5px;
            border-width: 1px 2px;
            border-style: solid;
            border-radius: 4px;
        }
        .wpne-badge-primary {
            border-color: #8a8a8a;
            background: #ffffff;
            color: #8a8a8a;
        }
        .wpne-badge-info {
            border-color: #00a0d2;
            background: #ecf8fa;
            color: #00a0d2;
        }
        .wpne-badge-success {
            border-color: #3aa043;
            background: #edffee;
            color: #3aa043;
        }
        .wpne-badge-warning {
            border-color: #e0a102;
            background: #fff9e8;
            color: #e0a102;
        }
        .wpne-badge-danger {
            border-color: #dc3232;
            background: #f8e8e8;
            color: #dc3232;
        }
        .wpne-modal-form {
            display: none;
        }
        .wpne-tabs-content {
            padding: 5px 1px 0;
        }
        .wpne-tabs-content > div {
            display: none;
        }
        .wpne_form_tag_block {
            width: 99%;
        }
        .wpne_form_tag_block .wpne-tabs a {
            padding: 0 10px;
        }
    </style>

    <script type="text/javascript">
        (function($) {
            $(document).on('keyup keypress blur change input', '.wpne-form-group.wpne-has-error input, .wpne-form-group.wpne-has-error textarea, .wpne-form-group.wpne-has-error select', function() {
                $(this).closest('.wpne-form-group').removeClass('wpne-has-error');
            });

            $(document).on('click', 'a[data-wpne-confirm]', function() {
                const message = $(this).data('wpne-confirm');

                if (!confirm(message)) {
                    return false;
                }
            });

            $.fn.wpne_modal = function() {
                const $form  = $(this);
                const id     = $form.attr('id');
                const title  = $form.data('title');
                const width  = $form.data('width');
                const height = $form.data('height');

                const url = '/?TB_inline&width=' + width + '&height=' + height + '&inlineId=' + id;

                tb_show(title, url);
            }

            $(document).on('click', '.wpne-modal', function() {
                const id = $(this).attr('href').substring(1);

                $('#' + id).wpne_modal();
            });

            $.fn.wpne_tabs = function() {
                const id = $(this).attr('for');

                $(this).find('a').click(function() {
                    const index = $(this).index();

                    const $tabs = $(this).parent().find('a');

                    $tabs.removeClass('nav-tab-active');
                    $($tabs[index]).addClass('nav-tab-active');

                    $(id + '>div').hide();
                    $($(id + '>div')[index]).show();

                    return false;
                });

                $(this).find('a:first').click();
            }

            $(window).load(function() {
                $('.wpne-tabs').each(function() {
                    $(this).wpne_tabs();
                });
            });
        })(jQuery);
    </script>

    <h1 class="wp-heading-inline">Notify.Events</h1>

    <?php Alert::display() ?>

    <div class="nav-tab-wrapper">

        <?= Html::a(__('About', WPNE), [
            'controller' => 'about',
        ], [
            'data-tab-name' => 'about',
            'class'         => 'nav-tab' . ($controller instanceof AboutController ? ' nav-tab-active' : ''),
        ]) ?>

        <?= Html::a(__('Channels', WPNE), [
            'controller' => 'channel',
        ], [
            'data-tab-name' => 'channel',
            'class'         => 'nav-tab' . ($controller instanceof ChannelController ? ' nav-tab-active' : ''),
        ]) ?>

        <?= Html::a(__('Events', WPNE), [
            'controller' => 'event',
        ], [
            'data-tab-name' => 'event',
            'class'         => 'nav-tab' . ($controller instanceof EventController ? ' nav-tab-active' : ''),
        ]) ?>

        <?= Html::a(__('Test', WPNE), [
            'controller' => 'test',
        ], [
            'data-tab-name' => 'test',
            'class'         => 'nav-tab' . ($controller instanceof TestController ? ' nav-tab-active' : ''),
        ]) ?>

    </div>

    <?= $content ?>
</div>
