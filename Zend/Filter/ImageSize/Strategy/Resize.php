<?php

/**
 * @see Zend_Filter_ImageSize_Strategy_Interface 
 */
require_once 'Zend/Filter/ImageSize/Strategy/Interface.php';

/**
 * Strategy for resizing the image by fitting the content into the given 
 * dimensions.
 */
class Zend_Filter_ImageSize_Strategy_Resize
    implements Zend_Filter_ImageSize_Strategy_Interface
{
    /**
     * Return canvas resized according to the given dimensions.
     * @param resource $image GD image resource
     * @param int $width Output width
     * @param int $height Output height
     * @return resource GD image resource
     */
    public function resize($image, $width, $height)
    {
        $origWidth = imagesx($image);
        $origHeight = imagesy($image);
        
        $resized = imagecreatetruecolor($width, $height);
        
        imagesavealpha($resized, true);

        $transColour = imagecolorallocatealpha($resized, 0, 0, 0, 127);
        imagefill($resized, 0, 0, $transColour);
        
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);
        
        return $resized;
    }
}