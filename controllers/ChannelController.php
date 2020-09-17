<?php

namespace notify_events\controllers;

use notify_events\helpers\Url;
use notify_events\models\Alert;
use notify_events\models\Channel;
use notify_events\models\ChannelList;
use notify_events\models\Controller;

/**
 * Class ChannelController
 * @package notify_events\controllers
 */
class ChannelController extends Controller
{
    /**
     * @return string
     */
    public function action_index()
    {
        $channels = new ChannelList();

        return $this->render('index', [
            'channels' => $channels,
        ]);
    }

    /**
     * @return string
     */
    public function action_create()
    {
        $channel = new Channel();

        if ($channel->load($_POST) && $channel->save()) {
            Alert::success(__('Channel created successfully!', WPNE));

            wp_redirect(Url::to([
                'controller' => 'channel',
            ]));

            exit;
        }

        return $this->render('create', [
            'channel' => $channel,
        ]);
    }

    /**
     * @return string
     */
    public function action_update()
    {
        $channel = Channel::findOne(absint($_GET['channel_id']));

        if ($channel->load($_POST) && $channel->save()) {
            Alert::success(__('Channel updated successfully!', WPNE));

            wp_redirect(Url::to([
                'controller' => 'channel',
            ]));

            exit;
        }

        return $this->render('update', [
            'channel' => $channel,
        ]);
    }

    /**
     *
     */
    public function action_delete()
    {
        $channel = Channel::findOne(absint($_GET['channel_id']));

        if ($channel->delete()) {
            Alert::success(__('Channel delete successfully!', WPNE));
        } else {
            Alert::error(__('Channel deletion error!', WPNE));
        }

        wp_redirect(Url::to([
            'controller' => 'channel',
        ]));

        exit;
    }
}