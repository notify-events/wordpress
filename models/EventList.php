<?php

namespace notify_events\models;

if (!class_exists( 'WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

use notify_events\helpers\Html;
use notify_events\helpers\Badge;
use WP_List_Table;

/**
 * Class EventList
 * @package notify_events
 */
class EventList extends WP_List_Table
{
    /**
     * @inheritDoc
     */
    public function __construct()
    {
        parent::__construct([
            'singular' => 'event',
            'plural'   => 'events',
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
            'enabled'  => __('Enabled', WPNE),
            'title'    => __('Title', WPNE),
            'channel'  => __('Channel', WPNE),
            'priority' => __('Priority', WPNE),
        ];
    }

    /**
     * @inheritDoc
     */
    public function prepare_items()
    {
        $event_classes = [];

        /** @var Module $module */
        foreach (Core::instance()->module_list() as $module) {
            foreach ($module->event_list() as $category => $event_list) {
                foreach ($event_list as $event_class) {
                    $event_classes[] = $event_class;
                }
            }
        }

        $this->items = Event::find([
            'meta_query' => [
                [
                    'key'   => '_wpne_event_class',
                    'value' => $event_classes,
                ],
            ],
        ]);

        $this->_column_headers = [
            $this->get_columns(),
            [],
            [],
        ];
    }

    /**
     * @param Event $event
     * @param string $column_name
     * @param string $primary
     * @return string
     */
    protected function handle_row_actions($event, $column_name, $primary)
    {
        if ($column_name !== $primary) {
            return '';
        }

        $actions = [
            'edit' => Html::a(__('Edit', WPNE), [
                'controller' => 'event',
                'action'     => 'update',
                'event_id'   => $event->id,
            ]),
            'delete' => Html::a(__('Delete', WPNE), [
                'controller' => 'event',
                'action'     => 'delete',
                'event_id'   => $event->id,
            ], [
                'data-wpne-confirm' => sprintf(__('Delete "%s" event?', WPNE), $event->title),
            ]),
        ];

        return $this->row_actions($actions);
    }

    /**
     * @inheritDoc
     */
    protected function get_primary_column_name()
    {
        return 'title';
    }

    /**
     * @inheritDoc
     */
    protected function extra_tablenav($which)
    {
        if ($which == 'top') {
            echo Html::a(__('Add event', WPNE),
            '#wpne-event-create', [
                'class' => 'button button-primary wpne-modal',
            ]);
        }
    }

    /**
     * @param Event $event
     * @return string
     */
    public function column_enabled($event)
    {
        if ($event->enabled) {
            return Badge::success(__('On', WPNE));
        } else {
            return Badge::danger(__('Off', WPNE));
        }
    }

    /**
     * @param Event $event
     * @return string
     */
    public function column_title($event)
    {
        $link = Html::a($event->title, [
            'controller' => 'event',
            'action'     => 'update',
            'event_id'   => $event->id,
        ], [
            'class'      => 'row-title',
            'aria-label' => $event->title,
        ]);

        return sprintf('<strong>%s</strong>', $link);
    }

    /**
     * @param Event $event
     * @return string
     */
    public function column_channel($event)
    {
        return esc_html($event->get_channel()->title);
    }

    /**
     * @param Event $event
     * @return string
     */
    public function column_priority($event)
    {
        return $event->priority_badges()[$event->priority];
    }
}