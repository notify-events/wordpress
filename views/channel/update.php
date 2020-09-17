<?php

/**
 * @var View $this
 * @var Channel $channel
 */

use notify_events\models\Channel;
use notify_events\models\View;

?>

<div id="notify-events-channel-update">

    <h2><?= esc_html($channel->title) ?></h2>

    <?= $this->render('_form', [
        'channel' => $channel,
    ]) ?>

</div>

