<?php

interface Polycast_Filter_ImageSize_Configuration_Interface 
{
    public function getWidth();
    public function getHeight();
    public function getQuality();
    public function getStrategy();
    public function getOverrideMode();
    public function getOutputImageType();
    
    public function setWidth($width);
    public function setHeight($height);
    public function setQuality($quality);
    public function setStrategy(Polycast_Filter_ImageSize_Strategy_Interface $strategy);
    public function setOverrideMode($mode);
    public function setOutputImageType($type);
}
