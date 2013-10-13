<?php

require_once("/controller/applicationController.php");
require_once("/view/HTMLPage.php");

session_start();

$applicationView = new \controller\applicationController();

$applicationView->runApplication();