<?php

namespace notify_events\controllers;

use notify_events\models\Controller;

/**
 * Class AboutController
 * @package notify_events\controllers
 */
class AboutController extends Controller
{
    /**
     * @return string
     */
    public function action_index()
    {
        return $this->render('index');
    }
}