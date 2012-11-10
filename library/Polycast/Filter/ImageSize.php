<?php

/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

/**
 * @see Polycast_Filter_ImageSize_Strategy_Fit
 */
require_once 'Zend/Filter/ImageSize/Strategy/Fit.php';

/**
 * @see Zend_Filter_Exception
 */
require_once 'Zend/Filter/Exception.php';

/**
 * Filter for resizing images.  
 */
class Polycast_Filter_ImageSize implements Zend_Filter_Interface
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
     * Overwrite only if input is newer than output.
     * If output is newer filter() will NOT thrown an exception but return
     * the path regularly. This can become useful for caching.
     * @var string
     */
    const OVERWRITE_CACHE_OLDER = 'cache_older';
    
    /**
     * Overwrite nothing. 
     * If something is in the way, an exception is thrown. 
     * This is the default.
     * @var string
     */
    const OVERWRITE_NONE = 'none';
    
    /**
     * Override everything.
     * Use this defensively.
     * @var unknown_type
     */
    const OVERWRITE_ALL = 'all';
    
    /**
     * Flag defining the behaviour regarding overwriting existing files.
     * By default nothing is overwritten. 
     * @var string
     */
    protected $_overwriteMode = self::OVERWRITE_NONE;
    
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
     * Directory to write output in.
     * @var string
     */
    protected $_outputDir = './';
    
    /**
     * Output filetype. This is one of the following: gif, jpeg, png, auto.
     * If set to auto (or null) the output format will be the same as the input.
     * @var string
     */
    protected $_type = 'auto';
    
    /**
     * Quality for JPEG output.
     * @var unknown_type
     */
    protected $_quality = 100;
    
    /**
     * Wheter to crop the image or not.
     * @var boolean
     */
    protected $_crop = false;
    
    /**
     * Resizing strategy.
     * @var Polycast_Filter_ImageSize_Strategy_Interface
     */
    protected $_strategy = null;
    
    /**
     * Returns the result of filtering $value
     *
     * @param  string $value Path to an image.
     * @throws Zend_Filter_Exception If filtering $value is impossible
     * @return string Path to the resized image.
     */
    public function filter($value)
    {
        if (!extension_loaded('gd')) {
            throw new Zend_Filter_Exception('GD extension is not available. Can\'t process image.');
        }
        
        if(!file_exists($value)) {
            throw new Zend_Filter_Exception('Image does not exist: ' . $value);    
        }
        
        $outputPath = $this->getThumbnailPath($value);
        
        if(file_exists($outputPath)) {
            switch($this->_overwriteMode) {
                case self::OVERWRITE_ALL:
                    // just do it.
                    break;
                case self::OVERWRITE_CACHE_OLDER:
                    if(filemtime($value) < filemtime($outputPath)) {
                        // noting to do.
                        return $outputPath; 
                    }
                    break;
                case self::OVERWRITE_NONE:
                    throw new Zend_Filter_Exception('Can\'t create thumbnail. File already exists: ' . $outputPath);
                default:
                    break;
            }
        }
        
        $resized = $this->_resize($value);
        $type = $this->getType($value);
        return $this->_writeImage($resized, $type, $outputPath);
    }
    
    /**
     * Load the image and resize it using the assigned strategy.
     * @param string $filename Filename of the input file.
     * @return resource GD image resource.
     */
    protected function _resize($filename)
    {
        $image = imageCreateFromString(file_get_contents($filename));
        if(false === $image) {
            throw new Zend_Filter_Exception('Can\'t load image: ' . $filename);
        }
        
        $resized = $this->getStrategy()->resize(
            $image, $this->getWidth(), $this->getHeight());
        
        return $resized;
    }
    
    /**
     * Set the JPEG output compression.
     * @param int $q A value between 1 and 100.
     * @return Polycast_Filter_ImageSize Fluent interface
     */
    public function setQuality($q)
    {
        if($q > 100) {
            $q = 100;
        } elseif($q < 1) {
            $q = 1;
        }
        $this->_quality = intval($q);
        return $this;
    }
    
    /**
     * Get the JPEG output compression.
     * @return int
     */
    public function getQuality()
    {
        return $this->_quality;   
    }
    
    /**
     * Set the strategy for resizing.
     * @param Polycast_Filter_ImageSize_Strategy_Interface $strategy
     * @return Polycast_Filter_ImageSize Fluent interface
     */
    public function setStrategy(Polycast_Filter_ImageSize_Strategy_Interface $strategy)
    {
        $this->_strategy = $strategy;
        return $this;
    }
    
    /**
     * Returns the strategy for resizing.
     * @return Polycast_Filter_ImageSize_Strategy_Interface
     */
    public function getStrategy()
    {
        if(is_null($this->_strategy)) {
            $this->_strategy = new Polycast_Filter_ImageSize_Strategy_Fit();
        }
        return $this->_strategy;
    }

    /**
     * Set the output width in pixels.
     * @param int $width
     * @return Polycast_Filter_ImageSize Fluent interface
     */
    public function setWidth($width) 
    {
        $this->_width = $width > 0 ? $width : 1;
        return $this;
    }
    
    /**
     * Get the output width in pixels.
     * @return int
     */
    public function getWidth()
    {
        return $this->_width;   
    }
    
    /**
     * Set the output height in pixels.
     * @param int $height
     * @return Polycast_Filter_ImageSize Fluent interface
     */
    public function setHeight($height)
    {
        $this->_height = $height > 0 ? $height : 1;
        return $this;
    }
    
    /**
     * Get the output height in pixels.
     * @return int
     */
    public function getHeight()
    {
        return $this->_height;    
    }
    
    /**
     * Set the directory to save output in.
     * @param string $dir
     * @return Polycast_Filter_ImageSize Fluent interface
     */
    public function setThumnailDirectory($dir)
    {
        $this->_outputDir = $dir;
        return $this;
    }
    
    /**
     * Get the directory where the output is saved in.
     * @return string
     */
    public function getThumbnailDirectory()
    {
        return $this->_outputDir;
    }
    
    /**
     * Calculates the path where the thumbnail of a given file is going to be
     * saved.
     * 
     * @param $filename
     * @return string
     */
    public function getThumbnailPath($filename)
    {
        return $this->getThumbnailDirectory() 
            . DIRECTORY_SEPARATOR 
            . $this->getThumbnailBasename($filename);
    }
    
    /**
     * Calculate the filename of a thumbnail by a given filename.
     * 
     * The thumbnail filename is built like this: 
     * <basename>-<width>x<height>[.<extension>]
     * 
     * @param $filename string
     * @return string
     */
    public function getThumbnailBasename($filename)
    {
        $chunks = explode('.', strrev(basename($filename)), 2);
        $basename = strrev(array_pop($chunks));
        $ext = strrev(array_pop($chunks));
        
        switch($this->getType($filename)) {
            case 'jpeg': $ext = '.jpg'; break;
            case 'gif': $ext = '.gif'; break;
            case 'png': $ext = '.png'; break;
            
            case 'auto':
            case null:
            default:
                $ext = ".$ext";
        } 
        
        return sprintf('%s-%sx%s%s',
            $basename,
            $this->getWidth(),
            $this->getHeight(),
            $ext 
        );
    }
    
    /**
     * Set the output filetype. This can be one of the following values:
     * jpeg, png, gif, auto or NULL.
     * 
     * @param string | null $type
     * @return Polycast_Filter_ImageSize Fluent interface
     */
    public function setType($type)
    {
        if(!in_array($type, array('jpeg', 'png', 'gif', 'auto', null))) {
            throw new Zend_Filter_Exception('Unsupported output type: ' . $type);
        }
        $this->_type = $type;
        return $this;
    }
    
    /**
     * Get the output filetype.
     * @param string $path
     * @return string
     */
    public function getType($path)
    {
        if(is_null($this->_type) || 'auto' == $this->_type) {
            $fileinfo = getimagesize($path);
            switch($fileinfo[2]) {
                case IMAGETYPE_GIF:
                    $outputType = 'gif';
                    break;
                case IMAGETYPE_PNG:
                    $outputType = 'png';
                    break;
                default:
                case IMAGETYPE_JPEG:
                    $outputType = 'jpeg';
                    break;
            }
            // don't override $this->_type. 
            // Must remain the same for the next run.
            return $outputType; 
        } else {
            return $this->_type;
        }
    }
    
    /**
     * Get overwrite mode.
     * @return string
     */
    public function getOverwriteMode()
    {
        return $this->_overwriteMode;
    }
    
    /**
     * Set overwrite mode.
     * @param string $mode
     * @return Polycast_Filter_ImageSize Fluent interface
     */
    public function setOverwriteMode($mode)
    {
        if((!in_array($mode, array(self::OVERWRITE_ALL, self::OVERWRITE_NONE, self::OVERWRITE_CACHE_OLDER)))) {
            throw new Zend_Filter_Exception('Unsupported overwrite mode: ' . $mode);            
        }
        $this->_overwriteMode = $mode;
        return $this;
    }
    
    /**
     * Write the image to disk.
     *
     * @param resource $resource GD resource
     * @param string $origFilename
     * @return string
     */
    protected function _writeImage($resource, $type, $outputPath) 
    {
        $writeFunc = 'image' . $type;
        if('jpeg' == $type) {
            $writeFunc($resource, $outputPath, $this->getQuality());
        } else {
            $writeFunc($resource, $outputPath);
        }
        return $outputPath;
    }
}