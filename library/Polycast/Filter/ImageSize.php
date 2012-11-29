<?php
// Copyright (c) 2009-2012 Andreas Baumgart
// 
// This source file is subject to the MIT license that is bundled with this 
// package in the file LICENSE.txt.

/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

/**
 * @see Polycast_Filter_ImageSize_Strategy_Fit
 */
require_once 'Polycast/Filter/ImageSize/Strategy/Fit.php';

/**
 * @see Polycast_Filter_ImageSize_PathBuilder_Interface
 */
require_once 'Polycast/Filter/ImageSize/PathBuilder/Interface.php';

/**
 * @see Polycast_Filter_ImageSize_PathBuilder_Standard
 */
require_once 'Polycast/Filter/ImageSize/PathBuilder/Standard.php';

/**
 * @see Polycast_Filter_ImageSize_Configuration_Standard
 */
require_once 'Polycast/Filter/ImageSize/Configuration/Standard.php';

/**
 * @see Zend_Filter_Exception
 */
require_once 'Zend/Filter/Exception.php';

/**
 * Filter for resizing images using a given strategy. 
 */
class Polycast_Filter_ImageSize implements Zend_Filter_Interface
{
    /**
     * Override files only if older. This overwrite mode can be used for 
     * caching.
     */
    const OVERWRITE_CACHE_OLDER = 'cache_older';
    
    /**
     * Never overwrite an existing file. Every attempt will result in an 
     * exception.
     */
    const OVERWRITE_NONE = 'none';
    
    /**
     * Don't care to overwrite files. 
     */
    const OVERWRITE_ALL = 'all';

    const TYPE_PNG = 'png';
    const TYPE_JPEG = 'jpeg';
    const TYPE_GIF = 'gif';

    /** @var Polycast_Filter_ImageSize_PathBuilder_Interface */
    protected $_pathBuilder = null;

    /** @var Polycast_Filter_ImageSize_Configuration_Interface */
    protected $_config = null;

    /**
     * Name of the filename passed to filter(). Used for processing. 
     * @var string
     */
    protected $_inputFilename = null;
    
    /**
     * The image resource of the resized image. Set after _resize() was called.
     * @var resource
     */
    protected $_resizedImage = null;
    
    /**
     * Constructor.
     */
    public function __construct(Polycast_Filter_ImageSize_Configuration_Interface $config = null)
    {
        $this->_checkDependencies();
        $this->_config = $config;
    }
    
    /**
     * Returns the configuration object of the filter. If no configuration 
     * object has been assigned before a standard configuration will be created
     * on first call.
     * 
     * @return Polycast_Filter_ImageSize_Configuration_Interface
     */
    public function getConfig()
    {
        if (null === $this->_config) {
            $this->_config = new Polycast_Filter_ImageSize_Configuration_Standard();
        }
        return $this->_config;
    }
    
    /**
     * Sets the configuration object of the filter. Replaces existing 
     * configuration.
     * 
     * @param Polycast_Filter_ImageSize_Configuration_Interface $configSet
     */
    public function setConfig(Polycast_Filter_ImageSize_Configuration_Interface $config)
    {
        $this->_config = $config;
    }
    
    /**
     * Returns the Polycast_Filter_ImageSize_PathBuilder_Interface instance
     * used to build the path of output files.
     * 
     * @return Polycast_Filter_ImageSize_PathBuilder_Interface
     */
    public function getOutputPathBuilder()
    {
        if (null === $this->_pathBuilder) {
            $this->_pathBuilder = new Polycast_Filter_ImageSize_PathBuilder_Standard('./');
        }
        return $this->_pathBuilder;
    }
    
    /**
     * Sets the Polycast_Filter_ImageSize_PathBuilder_Interface instance used
     * to build the path of output files.
     * 
     * @param Polycast_Filter_ImageSize_PathBuilder_Interface $pathBuilder
     * @return Polycast_Filter_ImageSize
     */
    public function setOutputPathBuilder(Polycast_Filter_ImageSize_PathBuilder_Interface $pathBuilder)
    {
        $this->_pathBuilder = $pathBuilder;
        return $this;
    }
    
    /**
     * Returns the output path for a given input file.
     * 
     * If you use this method to predict the output name before the call to
     * filter(), keep in mind, that under some circumstances the returned path
     * may not match the result of filter(). This is only the case if the
     * attached path builder instance uses criteria which are not part of the 
     * filter's configuration (e.q. timestamps). 
     * 
     * @param string $filename
     * @return string
     * @throws Zend_Filter_Exception
     */
    public function getOutputPath($filename)
    {
        if (!$filename) {
            if (!$this->_inputFilename) {
                throw new Zend_Filter_Exception('$filename omitted');
            }
            $filename = $this->_inputFilename;
        }
        return $this->getOutputPathBuilder()->buildPath($filename, $this->getConfig());
    }
    
    /**
     * Returns the result of filtering $value
     *
     * @param  string $value Path to an image.
     * @throws Zend_Filter_Exception If filtering $value is impossible
     * @return string Path to the resized image.
     */
    public function filter($value)
    {
        $this->_setInputFilename($value); 
        
        if (!$this->_isCached()) {
            
            $this->_checkWritePermissions();
            $this->_resize();
            $this->_writeImage();
        }
        
        return $this->_getOutputPathOfCurrentFile();
    }
    
    protected function _setInputFilename($filename)
    {
        $this->_inputFilename = $filename;
        $this->_verifyInputFileExists();
    }
    
    protected function _checkDependencies()
    {
        if (!extension_loaded('gd')) {
            throw new Zend_Filter_Exception('GD extension is not available. Can\'t process images.');
        }
    }
    
    protected function _verifyInputFileExists()
    {
        if(!file_exists($this->_inputFilename)) {
            throw new Zend_Filter_Exception('Image does not exist: ' . $this->_inputFilename); 
        }
    }
    
    protected function _isCached()
    {
        if ($this->getConfig()->getOverwriteMode() == self::OVERWRITE_CACHE_OLDER) {
            
            $outputPath = $this->_getOutputPathOfCurrentFile();
            if (filemtime($this->_inputFilename) < filemtime($outputPath)) {
                return true;
            }
        }
        return false;
    }
    
    protected function _checkWritePermissions()
    {
        if ($this->getConfig()->getOverwriteMode() == self::OVERWRITE_NONE) {
            
            $outputPath = $this->_getOutputPathOfCurrentFile();
            if (file_exists($outputPath)) {
                throw new Zend_Filter_Exception('Can\'t create thumbnail. File already exists: ' . $outputPath . '. Use $this->getConfig()->setOverrideMode(Polycast_Filter_ImageSize::OVERWRITE_ALL) to allow overriding existing files.');
            }
        }
    }
    
    protected function _resize()
    {
        $image = imagecreatefromstring(file_get_contents($this->_inputFilename));
        if(false === $image) {
            throw new Zend_Filter_Exception('Can\'t load image: ' . $this->_inputFilename);
        }
        
        $strategy = $this->getConfig()->getStrategy();
        $width = $this->getConfig()->getWidth();
        $height = $this->getConfig()->getHeight();
        
        $this->_resizedImage = $strategy->resize($image, $width, $height);
    }
    
    protected function _getOutputPathOfCurrentFile()
    {
        return $this->getOutputPath($this->_inputFilename);
    }
    
    public function _getOutputImageType()
    {
        $configuredType = $this->getConfig()->getOutputImageType();
        
        if(!$configuredType || 'auto' == $configuredType) {
            
            $fileinfo = getimagesize($this->_inputFilename);
            switch($fileinfo[2]) {
                case IMAGETYPE_GIF:
                    $detectedType = self::TYPE_GIF;
                    break;
                case IMAGETYPE_PNG:
                    $detectedType = self::TYPE_PNG;
                    break;
                case IMAGETYPE_JPEG:
                    $detectedType = self::TYPE_JPEG;
                    break;
                default:
                    throw new Zend_Filter_Exception('Failed to detect input image type.');
            }
            return $detectedType; 
            
        } else {
            return $configuredType;
        }
    }
    
    protected function _writeImage() 
    {
        $outputPath = $this->_getOutputPathOfCurrentFile(); 
        $type = $this->_getOutputImageType();
        
        $writeFunc = 'image' . $type;
        if(self::TYPE_JPEG == $type) {
            $writeFunc($this->_resizedImage, $outputPath, $this->getConfig()->getQuality());
        } else {
            $writeFunc($this->_resizedImage, $outputPath);
        }
    }
}
