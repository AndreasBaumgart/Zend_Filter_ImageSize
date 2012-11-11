<?php

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

    
    public function getWidth()
    {
        return $this->_width;
    }
    
    public function getHeight()
    {
        return $this->_height;
    }
    
    public function getQuality()
    {
        return $this->_quality;
    }
    
    public function getStrategy()
    {
        return $this->_strategy;
    }
    
    public function getOverwriteMode()
    {
        return $this->_overrideMode;
    }
    
    /**
     * Get the output filetype.
     * @return string
     */
    public function getOutputImageType()
    {
        return $this->_type;
    }
    
    public function setWidth($width)
    {
        if ($width < 1) {
            throw new Zend_Filter_Exception('Image width can not be less than 1');
        }
        $this->_width = (int) $width;
        return $this;
    }
    
    public function setHeight($height)
    {
        if ($height < 1) {
            throw new Zend_Filter_Exception('Image height can not be less than 1');
        }
        $this->_height = (int) $height;
        return $this;
    }
    
    public function setQuality($quality)
    {
        if ($quality < 1 || $quality > 100) {
            throw new Zend_Filter_Exception('Image quality must be a integer from 1 to 100.');
        }
        $this->_quality = (int) $quality;
        return $this;
    }
    
    public function setStrategy(Polycast_Filter_ImageSize_Strategy_Interface $strategy)
    {
        $this->_strategy = $strategy;
        return $this;
    }
    
    public function setOverwriteMode($mode)
    {
        $this->_overrideMode = $mode;
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
