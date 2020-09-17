<?php

namespace notify_events\controllers;

use ErrorException;
use notify_events\helpers\Url;
use notify_events\models\Alert;
use notify_events\models\Controller;
use notify_events\models\Test;

/**
 * Class TestController
 * @package notify_events\controllers
 */
class TestController extends Controller
{
    /**
     * @return string
     * @throws ErrorException
     */
    public function action_index()
    {
        $test = new Test();

        if ($test->load($_POST) && $test->send()) {
            Alert::success(__('Message send successfully!', WPNE));

            wp_redirect(Url::to([
                'controller' => 'test',
            ]));

            exit();
        }

        return $this->render('index', [
            'test' => $test,
        ]);
    }
}