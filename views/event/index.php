<?php

/**
 * @var View $this
 * @var Module[] $modules
 * @var EventList $events
 */

use notify_events\helpers\Html;
use notify_events\models\Event;
use notify_events\models\EventList;
use notify_events\models\Module;
use notify_events\models\View;

?>

<style type="text/css">
    .column-enabled {
        width: 10%;
    }
    .column-channel,
    .column-priority {
        width: 15%;
    }
    .module-list h3 {
        margin-bottom: 4px;
    }
    .group-list h4 {
        margin: 2px 0;
    }
    .event-list {
        display: flex;
        flex-wrap: wrap;
    }
    .event-list .button {
        width: 45%;
        margin: 2px;
    }
</style>

<div id="notify-events-events">

    <h2><?= __('Event list', WPNE) ?></h2>

    <?php $events->display() ?>

    <div id="wpne-event-create" class="wpne-modal-form" data-title="<?= esc_attr(__('Select event type', WPNE)) ?>" data-width="350" data-height="400">
        <div class="module-list">
            <?php foreach ($modules as $module) { ?>
                <div>
                    <h3><?= $module::module_title() ?></h3>

                    <div class="group-list">
                        <?php foreach ($module->event_list() as $group_title => $events) { /** @var Event $event */ ?>
                            <div>
                                <h4><?= esc_html($group_title) ?></h4>

                                <div class="event-list">
                                    <?php foreach ($events as $event) { ?>
                                        <?= Html::a($event::event_title(), [
                                            'controller' => 'event',
                                            'action'     => 'create',
                                            'event'      => rawurlencode($event),
                                        ], [
                                            'class' => 'button',
                                        ]) ?>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

</div>
