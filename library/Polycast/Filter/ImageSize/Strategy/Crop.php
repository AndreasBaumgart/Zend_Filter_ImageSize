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
 * Strategy for resizing the image so that its smalles edge fits into the frame.
 * The rest is cropped.
 */
class Polycast_Filter_ImageSize_Strategy_Crop 
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
        
        $ratio = min(array($origWidth, $origHeight)) / max($width, $height);
        
        $w = $origWidth * $ratio;
        $h = $origHeight * $ratio;
        
        $cropped = imagecreatetruecolor($width, $height);
        imagecopyresampled($cropped, $image, 0, 0, 0, 0, $origWidth, $origHeight, $w, $h);
        return $cropped;
    }
}