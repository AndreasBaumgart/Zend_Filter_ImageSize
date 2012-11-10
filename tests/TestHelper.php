<?php

error_reporting(E_ALL | E_STRICT);

define('TESTING_BASE_DIR', dirname(__FILE__));
define('TESTING_ASSETS_DIR', TESTING_BASE_DIR . '/_files/');
define('TESTING_TMP_DIR', TESTING_BASE_DIR . '/_tmp/');

require '../vendor/autoload.php';

require_once 'PHPUnit/Framework/TestCase.php';
