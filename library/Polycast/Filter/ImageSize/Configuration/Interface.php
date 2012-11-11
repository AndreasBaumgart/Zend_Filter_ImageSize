<?php
// Copyright (c) 2009-2012 Andreas Baumgart
// 
// This source file is subject to the MIT license that is bundled with this 
// package in the file LICENSE.txt.

/**
 * Interface describing a configuration used by Polycast_Filter_ImageSize.
 */
interface Polycast_Filter_ImageSize_Configuration_Interface 
{
    /**
     * Returns the width of the output in pixels.
     * 
     * @return int
     */
    public function getWidth();
    
    /**
     * Returns the height of the output in pixels.
     * 
     * @return int
     */
    public function getHeight();

    /**
     * Returns the quality of the output.
     * 
     * @return int
     */
    public function getQuality();
    
    /**
     * Returns the strategy used for resizing.
     * 
     * @return Polycast_Filter_ImageSize_Strategy_Interface
     */
    public function getStrategy();
    
    /**
     * Returns the name of the ruleset which is applied when an output file
     * already exist. 
     * 
     * @return string
     */
    public function getOverwriteMode();
    
    /**
     * Returns the image type configured for output images.
     * 
     * @return string
     */
    public function getOutputImageType();
    
    /**
     * Sets the width in pixels.
     * 
     * @param int $width An integer >= 1
     * @return Polycast_Filter_ImageSize_Configuration_Interface Fluent interface
     */
    public function setWidth($width);
    
    /**
     * Sets the height in pixels.
     * @param int $height An integer >= 1
     * @return Polycast_Filter_ImageSize_Configuration_Interface Fluent interface
     */
    public function setHeight($height);
    
    /**
     * Sets the quality of the image.
     * The quality is defined as an integer value ranging from 1 to 100.
     * 
     * @param int $width An integer >= 1 && <= 100
     * @return Polycast_Filter_ImageSize_Configuration_Interface Fluent interface
     */
    public function setQuality($quality);
    
    /**
     * Sets the strategy used for resizing the image.
     * 
     * @param Polycast_Filter_ImageSize_Strategy_Interface $strategy
     * @return Polycast_Filter_ImageSize_Configuration_Interface Fluent interface
     */
    public function setStrategy(Polycast_Filter_ImageSize_Strategy_Interface $strategy);
    
    /**
     * Sets the name of the ruleset which is applied when an output file already
     * exists.
     * 
     * @param string $mode
     * @return Polycast_Filter_ImageSize_Configuration_Interface Fluent interface
     */
    public function setOverwriteMode($mode);
    
    /**
     * Sets the image type configured for output images.
     * 
     * @param string $type
     * @return Polycast_Filter_ImageSize_Configuration_Interface Fluent interface
     */
    public function setOutputImageType($type);
}
