<?php
// Copyright (c) 2009-2012 Andreas Baumgart
// 
// This source file is subject to the MIT license that is bundled with this 
// package in the file LICENSE.txt.

class Polycast_Filter_ImageSize_Configuration_Standard implements Polycast_Filter_ImageSize_Configuration_Interface 
{
    /**
     * Default output width.
     * @var int
     */
    const DEFAULT_WIDTH = 32;
    
    /**
     * Default output height.
     * @var int
     */
    const DEFAULT_HEIGHT = 32;
    
    /**
     * Flag defining the behaviour regarding overwriting existing files.
     * By default nothing is overwritten. 
     * @var string
     */
    protected $_overwriteMode = Polycast_Filter_ImageSize::OVERWRITE_NONE;
    
    /**
     * Width of output image.
     * @var integer
     */
    protected $_width = self::DEFAULT_WIDTH;
    
    /**
     * Height of output image.
     * @var integer
     */
    protected $_height = self::DEFAULT_HEIGHT;
    
    /**
     * Quality for JPEG output.
     * @var unknown_type
     */
    protected $_quality = 100;
    
    /**
     * Resizing strategy.
     * @var Polycast_Filter_ImageSize_Strategy_Interface
     */
    protected $_strategy = null;
    
    /**
     * Output filetype. This is one of the following: gif, jpeg, png, auto.
     * If set to auto (or null) the output format will be the same as the input.
     * @var string
     */
    protected $_type = 'auto';

    /**
     * Returns the width of the output in pixels.
     * 
     * @return int
     */
    public function getWidth()
    {
        return $this->_width;
    }
    
    /**
     * Returns the height of the output in pixels.
     * 
     * @return int
     */
    public function getHeight()
    {
        return $this->_height;
    }
    
    /**
     * Returns the quality of the output.
     * 
     * @return int
     */
    public function getQuality()
    {
        return $this->_quality;
    }
    
    /**
     * Returns the strategy used for resizing.
     * 
     * @return Polycast_Filter_ImageSize_Strategy_Interface
     */
    public function getStrategy()
    {
        if (null === $this->_strategy) {
            $this->_strategy = new Polycast_Filter_ImageSize_Strategy_Fit();
        }
        return $this->_strategy;
    }
    
    /**
     * Returns the name of the ruleset which is applied when an output file
     * already exist. 
     * 
     * @return string
     */
    public function getOverwriteMode()
    {
        return $this->_overwriteMode;
    }
    
    /**
     * Returns the image type configured for output images.
     * 
     * @return string
     */
    public function getOutputImageType()
    {
        return $this->_type;
    }
    
    /**
     * Sets the width in pixels.
     * 
     * @param int $width An integer >= 1
     * @return Polycast_Filter_ImageSize_Configuration_Interface Fluent interface
     */
    public function setWidth($width)
    {
        if ($width < 1) {
            throw new Zend_Filter_Exception('Image width can not be less than 1');
        }
        $this->_width = (int) $width;
        return $this;
    }
    
    /**
     * Sets the height in pixels.
     * @param int $height An integer >= 1
     * @return Polycast_Filter_ImageSize_Configuration_Interface Fluent interface
     */
    public function setHeight($height)
    {
        if ($height < 1) {
            throw new Zend_Filter_Exception('Image height can not be less than 1');
        }
        $this->_height = (int) $height;
        return $this;
    }
    
    /**
     * Sets the quality of the image.
     * The quality is defined as an integer value ranging from 1 to 100.
     * 
     * @param int $width An integer >= 1 && <= 100
     * @return Polycast_Filter_ImageSize_Configuration_Interface Fluent interface
     */
    public function setQuality($quality)
    {
        if ($quality < 1 || $quality > 100) {
            throw new Zend_Filter_Exception('Image quality must be a integer from 1 to 100.');
        }
        $this->_quality = (int) $quality;
        return $this;
    }
    
    /**
     * Sets the strategy used for resizing the image.
     * 
     * @param Polycast_Filter_ImageSize_Strategy_Interface $strategy
     * @return Polycast_Filter_ImageSize_Configuration_Interface Fluent interface
     */
    public function setStrategy(Polycast_Filter_ImageSize_Strategy_Interface $strategy)
    {
        $this->_strategy = $strategy;
        return $this;
    }
    
    /**
     * Sets the name of the ruleset which is applied when an output file already
     * exists.
     * 
     * @param string $mode
     * @return Polycast_Filter_ImageSize_Configuration_Interface Fluent interface
     */
    public function setOverwriteMode($mode)
    {
        $this->_overwriteMode = $mode;
        return $this;
    }
    
    /**
     * Set the output filetype. This can be one of the following values:
     * jpeg, png, gif, auto or NULL.
     * 
     * @param string | null $type
     * @return Polycast_Filter_ImageSize Fluent interface
     */
    public function setOutputImageType($type)
    {
        if(!in_array($type, array('jpeg', 'png', 'gif', 'auto', null))) {
            throw new Zend_Filter_Exception('Unsupported output type: ' . $type);
        }
        $this->_type = $type;
        return $this;
    }
}
