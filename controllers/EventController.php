<?php

namespace notify_events\controllers;

use notify_events\helpers\Inflector;
use notify_events\helpers\Url;
use notify_events\models\Alert;
use notify_events\models\Controller;
use notify_events\models\Core;
use notify_events\models\Event;
use notify_events\models\EventList;

/**
 * Class EventController
 * @package notify_events\controllers
 */
class EventController extends Controller
{
    /**
     * @return string
     */
    public function action_index()
    {
        $events  = new EventList();
        $modules = Core::instance()->module_list();

        return $this->render('index', [
            'events'  => $events,
            'modules' => $modules,
        ]);
    }

    /**
     * @return string
     */
    public function action_create()
    {
        /** @var Event $event_class */
        $event_class = wp_unslash($_GET['event']);

        if (!is_subclass_of($event_class, Event::class)) {
            wp_die('Invalid event type');
        }

        /** @var Event $event */
        $event = new $event_class;

        $preview_subject = false;
        $preview_message = false;

        if ($event->load($_POST)) {
            if (array_key_exists('preview', $_POST)) {
                $event->preview($preview_subject, $preview_message);
            } else {
                if ($event->save()) {
                    Alert::success(__('Event created successfully!', WPNE));

                    wp_redirect(Url::to([
                        'controller' => 'event',
                    ]));

                    exit;
                }
            }
        }

        if ($event::default_view()) {
            return $this->render('_form', [
                'event'           => $event,
                'preview_subject' => $preview_subject,
                'preview_message' => $preview_message,
            ]);
        } else {
            return $this->render(Inflector::id_from_class($event_class), [
                'event'           => $event,
                'preview_subject' => $preview_subject,
                'preview_message' => $preview_message,
            ], $event::module_name());
        }
    }

    /**
     * @return string
     */
    public function action_update()
    {
        $event = Event::findOne($_GET['event_id']);

        $preview_subject = false;
        $preview_message = false;

        if ($event->load($_POST)) {
            if (array_key_exists('preview', $_POST)) {
                $event->preview($preview_subject, $preview_message);
            } else {
                if ($event->save()) {
                    Alert::success(__('Event updated successfully!', WPNE));

                    wp_redirect(Url::to([
                        'controller' => 'event',
                    ]));

                    exit;
                }
            }
        }

        if ($event::default_view()) {
            return $this->render('_form', [
                'event'           => $event,
                'preview_subject' => $preview_subject,
                'preview_message' => $preview_message,
            ]);
        } else {
            return $this->render(Inflector::id_from_class($event->event_class), [
                'event'           => $event,
                'preview_subject' => $preview_subject,
                'preview_message' => $preview_message,
            ], $event::module_name());
        }
    }

    /**
     *
     */
    public function action_delete()
    {
        $event = Event::findOne($_GET['event_id']);

        if ($event->delete()) {
            Alert::success(__('Event delete successfully!', WPNE));
        } else {
            Alert::error(__('Event deletion error!', WPNE));
        }

        wp_redirect(Url::to([
            'controller' => 'event',
        ]));

        exit;
    }
}