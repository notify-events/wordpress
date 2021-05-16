<?php

/**
 * @var View  $this
 * @var Event $event
 */

use notify_events\models\Event;
use notify_events\models\View;

?>

<?= $this->render('form/_select', [
    'model' => $event,
    'title' => __('Message priority', WPNE),
    'field' => 'priority',
    'items' => $event->priority_labels(),
]) ?>
