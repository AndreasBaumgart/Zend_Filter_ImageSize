<?php
// Copyright (c) 2009-2012 Andreas Baumgart
// 
// This source file is subject to the MIT license that is bundled with this 
// package in the file LICENSE.txt.

class ExamplesTest extends ImageFilterTestCase
{
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
}
