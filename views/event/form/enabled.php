<?php

/**
 * @var View  $this
 * @var Event $event
 */

use notify_events\models\Event;
use notify_events\models\View;

?>

<?= $this->render('form/_checkbox', [
    'model' => $event,
    'title' => __('Enabled', WPNE),
    'field' => 'enabled',
]) ?>
