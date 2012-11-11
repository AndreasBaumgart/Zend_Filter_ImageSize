<?php
// Copyright (c) 2009-2012 Andreas Baumgart
// 
// This source file is subject to the MIT license that is bundled with this 
// package in the file LICENSE.txt.

/**
 * Generates output paths in the form: 
 *    thumbnailDir/basename-width-height.extension
 */
class Polycast_Filter_ImageSize_PathBuilder_Standard implements Polycast_Filter_ImageSize_PathBuilder_Interface
{
    /**
     * Name of the file currently being processed.
     * @var string
     */
    protected $_inputFilename = null;
    
    /**
     * Base directory for output paths.
     * @var string
     */
    protected $_thumbnailDir = null;
    
    /**
     * Configuration instance currently being processed. 
     * @var Polycast_Filter_ImageSize_Configuration_Interface 
     */
    protected $_config = null;
    
    /**
     * Constructor.
     * 
     * @param string $thumbnailDir The name of the base directory.
     */
    public function __construct($thumbnailDir) 
    {
        $this->_thumbnailDir = $thumbnailDir;
    }
    
    /**
     * Builds a new path from a given path according to the given configuration.
     * 
     * @param type $filename
     * @param Polycast_Filter_ImageSize_Configuration_Interface $config
     * @return string
     */
    public function buildPath($filename, Polycast_Filter_ImageSize_Configuration_Interface $config)
    {
        $this->_inputFilename = $filename;
        $this->_config = $config;
        
        $basename = $this->_getBasename();
        $path = $this->_thumbnailDir . DIRECTORY_SEPARATOR  . $basename;
        
        return $path;
    }
    
    protected function _getBasename()
    {
        $chunks = explode('.', strrev(basename($this->_inputFilename)), 2);
        $basename = strrev(array_pop($chunks));
        $ext = strrev(array_pop($chunks));
        
        switch($this->_config->getOutputImageType()) {
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
            $this->_config->getWidth(),
            $this->_config->getHeight(),
            $ext 
        );
    }
}
