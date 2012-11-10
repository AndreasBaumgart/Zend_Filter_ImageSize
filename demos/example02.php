<?php

// adjust setup.php according to your environment
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "setup.php";

require_once 'Polycast/Filter/ImageSize.php';
require_once 'Polycast/Filter/ImageSize/Strategy/Crop.php';

$filter = new Polycast_Filter_ImageSize();
$output = $filter->setHeight(32)
    ->setWidth(32)
    ->setQuality(50)
    ->setOverwriteMode(Polycast_Filter_ImageSize::OVERWRITE_ALL)
    ->setThumnailDirectory('./thumbnails')
    ->setType('jpeg')
    ->setStrategy(new Polycast_Filter_ImageSize_Strategy_Crop())
    ->filter('./rick.jpg');

echo "Output saved to: $output" . PHP_EOL;
