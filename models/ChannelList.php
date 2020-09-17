<?php

namespace notify_events\models;

if (!class_exists( 'WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

use notify_events\helpers\Html;
use WP_List_Table;

/**
 * Class ChannelList
 * @package notify_events
 */
class ChannelList extends WP_List_Table
{
    /**
     * @inheritDoc
     */
    public function __construct()
    {
        parent::__construct([
            'singular' => 'channel',
            'plural'   => 'channels',
            'ajax'     => false,
        ]);

        $this->prepare_items();
    }

    /**
     * @inheritDoc
     */
    public function get_columns()
    {
        return [
            'title' => __('Title', WPNE),
        ];
    }

    /**
     * @inheritDoc
     */
    public function prepare_items()
    {
        $this->items = Channel::find();

        $this->_column_headers = [
            $this->get_columns(),
            [],
            [],
        ];
    }

    /**
     * @param Channel $channel
     * @param string $column_name
     * @param string $primary
     * @return string
     */
    protected function handle_row_actions($channel, $column_name, $primary)
    {
        if ($column_name !== $primary) {
            return '';
        }

        $actions = [
            'edit' => Html::a(__('Edit', WPNE), [
                'controller' => 'channel',
                'action'     => 'update',
                'channel_id' => $channel->id,
            ]),
            'delete' => Html::a(__('Delete', WPNE), [
                'controller' => 'channel',
                'action'     => 'delete',
                'channel_id' => $channel->id,
            ], [
                'data-wpne-confirm' => sprintf(__('Delete "%s" channel?', WPNE), $channel->title),
            ]),
        ];

        return $this->row_actions($actions);
    }

    /**
     * @inheritDoc
     */
    protected function extra_tablenav($which)
    {
        if ($which == 'top') {
            echo Html::a(__('Add channel', WPNE), [
                'controller' => 'channel',
                'action'     => 'create',
            ], [
                'class' => 'button button-primary',
            ]);
        }
    }

    /**
     * @param Channel $channel
     * @return string
     */
    public function column_title($channel)
    {
        $link = Html::a($channel->title, [
            'controller' => 'channel',
            'action'     => 'update',
            'channel_id' => $channel->id,
        ], [
            'class'      => 'row-title',
            'aria-label' => $channel->title,
        ]);

        return sprintf('<strong>%s</strong>', $link);
    }
}