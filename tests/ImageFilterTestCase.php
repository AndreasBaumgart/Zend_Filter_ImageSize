<?php
// Copyright (c) 2009-2012 Andreas Baumgart
// 
// This source file is subject to the MIT license that is bundled with this 
// package in the file LICENSE.txt.

class ImageFilterTestCase extends PHPUnit_Framework_TestCase
{
    public $box;
    public $sourceImage;
    public $outputImage = null;
    public $outputPath;
    public $strategy;
    
    public function setUp() {
        
        parent::setUp();
        
        $this->box = array(10, 10);
        $this->sourceImage = TESTING_ASSETS_DIR . '/rick.jpg';
        $this->outputImage = null;
        $this->sourcePath = null;
        $this->strategy = null;
    }
    
    public function tearDown() {
        parent::tearDown();
        
        foreach (glob(TESTING_TMP_DIR . '/*.jpg') as $obsoleteJpegFile) {
            @unlink($obsoleteJpegFile);
        }
    }

    public function givenImageWithRatio($ratio)
    {
        $this->sourceImage = $this->_createImageWithRatio($ratio);
    }
    
    public function givenBoxWithRatio($ratio)
    {
        $this->box = array(100, round(100*$ratio));
    }
    
    public function givenFitStrategy()
    {
        $this->strategy = new Polycast_Filter_ImageSize_Strategy_Fit();
    }
    
    public function whenFilter()
    {
        if (!$this->strategy) {
            $this->givenFitStrategy();
        }
        
        $filter = new Polycast_Filter_ImageSize();
        $filter->setType('jpeg')
               ->setOverwriteMode(Polycast_Filter_ImageSize::OVERWRITE_ALL)
               ->setThumnailDirectory(TESTING_TMP_DIR)
               ->setWidth($this->box[0])
               ->setHeight($this->box[1])
               ->setStrategy($this->strategy);
        
        $this->outputPath = $filter->filter($this->sourceImage);
    }
    
    public function assertFilteredImageHasRatio($expectedRatio) 
    {
        $this->_loadOutputImageIfNecessary();
        $this->assertEquals(round($expectedRatio, 2), $this->_getOutputImageRatio(), 'Filtered image does not have the expected aspect ratio.');
    }
    
    public function assertFilteredImageHeightEqualsBoxHeight()
    {
        $this->_loadOutputImageIfNecessary();
        $this->assertEquals($this->box[1], imagesy($this->outputImage), 'Filtered image\'s height is not equals box height.');
    }
    
    public function assertFilteredImageWidthLesserThanBoxWidth()
    {
        $this->_loadOutputImageIfNecessary();
        $this->assertLessThan($this->box[0], imagesx($this->outputImage), 'Filtered image\'s width is not lesser than box width.');
    }
    
    public function assertFilteredImageWidthEqualsBoxWidth()
    {
        $this->_loadOutputImageIfNecessary();
        $this->assertEquals($this->box[0], imagesx($this->outputImage), 'Filtered image\'s width is not equals box width.');
    }
    
    public function assertFilteredImageHeightLesserThanBoxHeight()
    {
        $this->_loadOutputImageIfNecessary();
        $this->assertLessThan($this->box[1], imagesy($this->outputImage), 'Filtered image\'s height is not lesser than box height.');
    }
    
    public function assertImageFitsInBox($expectedWidth, $expectedHeight, $imagePath)
    {
        list($actualWidth, $actualHeight) = $this->getImageSize($imagePath);
        
        $atLeastOneSideEqualBoxSide = $actualWidth == $expectedWidth || $actualHeight == $expectedHeight;
        $noSideOverlaps = $actualWidth >= $expectedWidth && $actualHeight <= $expectedHeight;
        
        if (!$atLeastOneSideEqualBoxSide || !$noSideOverlaps) {
            $this->fail('Image does not fill the given box (' . $expectedWidth . 
                    ' x ' . $expectedHeight .'). Actual image dimenstions: ' . 
                    $actualWidth . ' x ' . $actualHeight . '.');
        }
    }
    
    public function assertImageSizeEquals($expectedWidth, $expectedHeight, $imagePath)
    {
        list($actualWidth, $actualHeight) = $this->getImageSize($imagePath);
        
        $this->assertEquals($expectedWidth, $actualWidth);
        $this->assertEquals($expectedHeight, $actualHeight);
    }
    
    public function getImageSize($imagePath) 
    {
        $img = imagecreatefromjpeg($imagePath);
        $actualWidth = imagesx($img);
        $actualHeight = imagesy($img);
        imagedestroy($img);    
        
        return array($actualWidth, $actualHeight);
    }
    
    private function _createImageWithRatio($ratio)
    {
        $path = TESTING_TMP_DIR . '/' . uniqid() . '.jpg';
        $image = imagecreate(100, round(100*$ratio));
        imagejpeg($image, $path);
        imagedestroy($image);
        return $path;
    }
    
    private function _loadOutputImageIfNecessary()
    {
        if (null === $this->outputImage) {
            $this->outputImage = imagecreatefromjpeg($this->outputPath);
        }
    }
    
    private function _getOutputImageRatio()
    {
        return round(imagesy($this->outputImage) / imagesx($this->outputImage), 2);
    }
}
