<?php

class ExamplesTest extends PHPUnit_Framework_TestCase
{
    public function tearDown() {
        parent::tearDown();
        
        foreach (glob(TESTING_TMP_DIR . '/*.jpg') as $obsoleteJpegFile) {
            @unlink($obsoleteJpegFile);
        }
    }
    
    public function testExample01_FitImageIntoABox()
    {
        $inputPath = TESTING_ASSETS_DIR . '/rick.jpg'; 
        $outputDirectory = TESTING_TMP_DIR; 
        
        $filter = new Polycast_Filter_ImageSize(); 
        $filter->setThumnailDirectory($outputDirectory)
               ->setWidth(100)
               ->setHeight(100)
               ->setQuality(50)
               ->setStrategy(new Polycast_Filter_ImageSize_Strategy_Fit())
               ->setOverwriteMode(Polycast_Filter_ImageSize::OVERWRITE_ALL)
        ; 
        $outputPath = $filter->filter($inputPath); 
        
        $this->assertEquals(true, is_file($outputPath));
        $this->assertImageFitsInBox(100, 100, $outputPath);
    }
    
    public function testExample01_CropImageToFitIntoABox()
    {
        $inputPath = TESTING_ASSETS_DIR . '/rick.jpg'; 
        $outputDirectory = TESTING_TMP_DIR; 
        
        $filter = new Polycast_Filter_ImageSize(); 
        $filter->setThumnailDirectory($outputDirectory)
               ->setWidth(100)
               ->setHeight(100)
               ->setQuality(50)
               ->setStrategy(new Polycast_Filter_ImageSize_Strategy_Crop())
               ->setOverwriteMode(Polycast_Filter_ImageSize::OVERWRITE_ALL)
        ; 
        $outputPath = $filter->filter($inputPath); 
        
        $this->assertEquals(true, is_file($outputPath));
        $this->assertImageSizeEquals(100, 100, $outputPath);        
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
}
