<?php

/**
 * @see Zend_Filter_ImageSize_Strategy_Interface 
 */
require_once 'Zend/Filter/ImageSize/Strategy/Interface.php';

/**
 * Strategy for resizing the image so that its smalles edge fits into the frame.
 * The rest is cropped.
 */
class Zend_Filter_Imagesize_Strategy_AutoSquareCrop
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
        
        $minSize = min(array($origWidth, $origHeight));
        
        $xSrc = 0;
        $ySrc = 0;
        
        if ($origWidth > $origHeight) {
            $xSrc = floor(($origWidth - $minSize) / 2 );
            
        }
        else {
            $ySrc = floor(($origHeight - $minSize) / 2 );
        }
        
        $cropped = imagecreatetruecolor($minSize, $minSize);
        imagecopyresampled($cropped, $image, 0, 0, $xSrc, $ySrc, $minSize, $minSize, $minSize, $minSize);
        
        return $cropped;
    }
}