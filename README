Zend Framework extension providing filter facitilies for image size.

Most simple example:

<?php
require_once 'Zend/Filter/ImageSize.php';
$filter = new Zend_Filter_ImageSize();
$output = $filter->setHeight(100)
    ->setWidth(200)
    ->filter('./orig.jpg');

header('Content-Type: image/jpeg');
$fh = fopen($output, 'r');
fpassthru($fh);
fclose($fh);
?>

More complex example:
 - Crop the image instead of fit it into the bounding box.
 - Output as JPEG (actually this is the same as above as it is the default)
 - Set a specific output directory (default = ./)
 - Use caching, i.e. override only if source image is newer than thumbnail (if exists)

<?php
require_once 'Zend/Filter/ImageSize.php';
require_once 'Zend/Filter/ImageSize/Strategy/Crop.php';

$filter = new Zend_Filter_ImageSize();
$output = $filter->setHeight(100)
    ->setWidth(200)
    ->setQuality(75)
    ->setOverwriteMode(Zend_Filter_ImageSize::OVERWRITE_ALL)
    ->setThumnailDirectory('./')
    ->setType('jpeg')
    ->setStrategy(new Zend_Filter_Imagesize_Strategy_Crop())
    ->filter('./orig.jpg');

header('Content-Type: image/jpeg');
$fh = fopen($output, 'r');
fpassthru($fh);
fclose($fh);

?>
