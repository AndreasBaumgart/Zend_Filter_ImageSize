<?php
// Copyright (c) 2009-2012 Andreas Baumgart
// 
// This source file is subject to the MIT license that is bundled with this 
// package in the file LICENSE.txt.

error_reporting(E_ALL | E_STRICT);

define('TESTING_BASE_DIR', dirname(__FILE__));
define('TESTING_ASSETS_DIR', TESTING_BASE_DIR . '/_assets/');
define('TESTING_TMP_DIR', TESTING_BASE_DIR . '/_tmp/');

require '../vendor/autoload.php';

require_once 'PHPUnit/Framework/TestCase.php';
