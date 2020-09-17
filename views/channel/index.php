<?php

/**
 * @var View $this
 * @var ChannelList $channels
 */

use notify_events\models\ChannelList;
use notify_events\models\View;

?>

<div id="notify-events-channels">

    <h2><?= __('Channel list', WPNE) ?></h2>

    <?php $channels->display() ?>

</div>
