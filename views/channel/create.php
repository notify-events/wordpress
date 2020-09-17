<?php

/**
 * @var View $this
 * @var Channel $channel
 */

use notify_events\models\Channel;
use notify_events\models\View;

?>

<div id="notify-events-channel-create">

    <h2><?= esc_html(__('New channel', WPNE)) ?></h2>

    <?= $this->render('_form', [
        'channel' => $channel,
    ]) ?>

</div>
