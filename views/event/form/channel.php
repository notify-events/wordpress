<?php

/**
 * @var View  $this
 * @var Event $event
 */

use notify_events\models\Core;
use notify_events\models\Event;
use notify_events\models\View;

?>

<?= $this->render('form/_multi_select', [
    'model' => $event,
    'title' => __('Channels', WPNE),
    'field' => 'channel_id',
    'items' => Core::channel_list(),
]) ?>
