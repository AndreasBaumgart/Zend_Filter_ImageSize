<?php

// adjust setup.php according to your environment
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "setup.php";

require_once 'Polycast/Filter/ImageSize.php';

$filter = new Polycast_Filter_ImageSize();
$output = $filter->setHeight(100)
    ->setWidth(200)
    ->setOverwriteMode(Polycast_Filter_ImageSize::OVERWRITE_ALL)
    ->filter('./rick.jpg');

echo "Output saved to: $output" . PHP_EOL;

