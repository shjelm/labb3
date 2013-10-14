<?php

require_once dirname(__FILE__) . '/Controller/applicationController.php';
require_once dirname(__FILE__) . '/View/HTMLPage.php';

session_start();

$applicationView = new \controller\applicationController();

$applicationView->runApplication();