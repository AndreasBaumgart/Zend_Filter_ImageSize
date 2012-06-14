<?php

/**
 * @see Zend_Filter_ImageSize_Strategy_Interface 
 */
require_once 'Zend/Filter/ImageSize/Strategy/Interface.php';

/**
 * Strategy for resizing the image by fitting the content into the given 
 * dimensions.
 */
class Zend_Filter_ImageSize_Strategy_FitFill 
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
        
        $rWidth = $origWidth / $width;
        $rHeight = $origHeight / $height;
        
        

        if($rWidth > $rHeight) {
            $ratio = $rWidth;
            
        } else {
            $ratio = $rHeight;
        }
        
        $newWidth = ceil($origWidth / $ratio);
        $newHeight = ceil($origHeight / $ratio);    
        
        $dstX = ($width - $newWidth) / 2;
        $dstY = ($height - $newHeight) / 2;
        
        $resized = imagecreatetruecolor($width, $height);
        
        imagesavealpha($resized, true);

        $transColour = imagecolorallocatealpha($resized, 0, 0, 0, 127);
        imagefill($resized, 0, 0, $transColour);
        
        imagecopyresampled($resized, $image, $dstX, $dstY, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
        return $resized;
    }
}