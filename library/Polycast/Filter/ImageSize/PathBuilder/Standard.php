<?php

class Polycast_Filter_ImageSize_PathBuilder_Standard implements Polycast_Filter_ImageSize_PathBuilder_Interface
{
    protected $_inputFilename = null;
    protected $_thumbnailDir = './';
    protected $_config = null;
    
    public function __construct($thumbnailDir) 
    {
        $this->_thumbnailDir = $thumbnailDir;
    }
    
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
