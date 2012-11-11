<?php
// Copyright (c) 2009-2012 Andreas Baumgart
// 
// This source file is subject to the MIT license that is bundled with this 
// package in the file LICENSE.txt.

class ExamplesTest extends ImageFilterTestCase
{
    public function testExample01_Minimal()
    {
        $filter = new Polycast_Filter_ImageSize();
        $filter->setOutputPathBuilder(new Polycast_Filter_ImageSize_PathBuilder_Standard((TESTING_TMP_DIR)));
        $filter->getConfig()
               ->setWidth(200)
               ->setHeight(100);
        $outputPath = $filter->filter(TESTING_ASSETS_DIR . '/rick.jpg');
        
        $this->assertFileExists($outputPath);
        $this->assertImageFitsInBox(200, 100, $outputPath);
    }
    
    public function testExample02_FitImageIntoABox()
    {
        $inputPath = TESTING_ASSETS_DIR . '/rick.jpg'; 
        $outputDirectory = TESTING_TMP_DIR; 
        $filter = new Polycast_Filter_ImageSize(); 
        $config = $filter->getConfig();
        
        $config->setWidth(100)
               ->setHeight(100)
               ->setQuality(50)
               ->setStrategy(new Polycast_Filter_ImageSize_Strategy_Fit())
               ->setOverwriteMode(Polycast_Filter_ImageSize::OVERWRITE_ALL)
               ->getOutputImageType('png');

        $filter->setOutputPathBuilder(new Polycast_Filter_ImageSize_PathBuilder_Standard($outputDirectory));
        
        // in some cases you might want to know the path of the file before it
        // is acutally written. then use this:
        $predictedOutputPath = $filter->getOutputPath($inputPath);
        
        $actualOutputPath = $filter->filter($inputPath); 
        
        $this->assertEquals($predictedOutputPath, $actualOutputPath);
        $this->assertFileExists($actualOutputPath);
        $this->assertImageFitsInBox(100, 100, $actualOutputPath);
    }
    
    public function testExample03_CropImageToFitIntoABox()
    {
        $inputPath = TESTING_ASSETS_DIR . '/rick.jpg'; 
        $outputDirectory = TESTING_TMP_DIR; 
        $filter = new Polycast_Filter_ImageSize(); 
        $config = $filter->getConfig();
        
        $config->setWidth(100)
               ->setHeight(100)
               ->setQuality(50)
               ->setStrategy(new Polycast_Filter_ImageSize_Strategy_Crop())
               ->setOverwriteMode(Polycast_Filter_ImageSize::OVERWRITE_ALL)
               ->getOutputImageType('png');
        
        $filter->setOutputPathBuilder(new Polycast_Filter_ImageSize_PathBuilder_Standard($outputDirectory));        
        $outputPath = $filter->filter($inputPath); 
        
        $this->assertEquals(true, is_file($outputPath));
        $this->assertImageSizeEquals(100, 100, $outputPath);        
    }
    
    public function testExample04_CustomOutputPathBuilder()
    {
        $inputPath = TESTING_ASSETS_DIR . '/rick.jpg'; 
        $outputDirectory = TESTING_TMP_DIR; 
        
        $filter = new Polycast_Filter_ImageSize(); 
        $config = $filter->getConfig();
        
        $config->setWidth(100)
               ->setHeight(100)
               ->setQuality(50)
               ->setStrategy(new Polycast_Filter_ImageSize_Strategy_Crop())
               ->setOverwriteMode(Polycast_Filter_ImageSize::OVERWRITE_ALL)
        ; 
        $filter->setOutputPathBuilder(new ExamplesTest_CustomPathBuilder($outputDirectory)); 
        $outputPath = $filter->filter($inputPath); 
        
        $this->assertEquals(true, is_file($outputPath));
        $this->assertImageSizeEquals(100, 100, $outputPath);        
    }
}

class ExamplesTest_NamedConfig extends Polycast_Filter_ImageSize_Configuration_Standard
{
    protected $_templateName = null;
    
    public function __construct($templateName)
    {
        $this->_templateName = $templateName;
    }
    
    public function getTemplateName()
    {
        return $this->_templateName;
    }
}

class ExamplesTest_CustomPathBuilder implements Polycast_Filter_ImageSize_PathBuilder_Interface
{
    private $_outputDir = null;
    
    public function __construct($outputDir) 
    {
        $this->_outputDir = $outputDir;
    }
    
    public function buildPath($filename, Polycast_Filter_ImageSize_Configuration_Interface $config) 
    {
        $chunks = explode('.', strrev(basename($filename)), 2);
        $basename = strrev(array_pop($chunks));
        $ext = strrev(array_pop($chunks));
        
        switch($config->getOutputImageType()) {
            
            case 'jpeg': $ext = '.jpg'; break;
            case 'gif': $ext = '.gif'; break;
            case 'png': $ext = '.png'; break;
            
            case 'auto':
            case null:
            default:
                $ext = ".$ext";
        } 
        
        if ($config instanceof ExamplesTest_NamedConfig) {
            $postfix = $config->getTemplateName();
        } else {
            $postfix = sprintf('%sx%s-q%s', $config->getWidth(), $config->getHeight(), $config->getQuality());
        }
        
        $path = sprintf('%s/%s-%s%s',
            $this->_outputDir,
            $basename,
            $postfix,
            $ext
        );
        
        return $path;
    }
}