<?php
// Copyright (c) 2009-2012 Andreas Baumgart
// 
// This source file is subject to the MIT license that is bundled with this 
// package in the file LICENSE.txt.

/**
 * @see Polycast_Filter_ImageSize_Strategy_Interface 
 */
require_once 'Polycast/Filter/ImageSize/Strategy/Interface.php';

/**
 * Strategy for resizing the image by fitting the content into the given 
 * dimensions.
 */
class Polycast_Filter_ImageSize_Strategy_Fit 
    implements Polycast_Filter_ImageSize_Strategy_Interface
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
            
        $origRatio = $origHeight / $origWidth;
        $newRatio = $height / $width;
        
        if ($newRatio <= $origRatio) {
            $newHeight = $height;
            $newWidth = $height / $origRatio;
            
        } else {
            $newWidth = $width;
            $newHeight = $width * $origRatio;
        }
        
        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
        
        return $resized;
    }
}