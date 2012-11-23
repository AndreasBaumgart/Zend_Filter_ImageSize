<?php
// Copyright (c) 2009-2012 Andreas Baumgart
// 
// This source file is subject to the MIT license that is bundled with this 
// package in the file LICENSE.txt.

class FitStrategyTest extends ImageFilterTestCase
{
    public function testFit_2x1_Image_in_3x1_Box()
    {
        $this->givenImageWithRatio(1/2);
        $this->givenBoxWithRatio(1/3);
        $this->givenFitStrategy();
        
        $this->whenFilter();
        
        $this->assertFilteredImageHasRatio(1/2);
        $this->assertFilteredImageHeightEqualsBoxHeight();
    }
    
    public function testFit_3x1_Image_in_2x1_Box()
    {
        $this->givenImageWithRatio(1/3);
        $this->givenBoxWithRatio(1/2);
        $this->givenFitStrategy();
        
        $this->whenFilter(); 
        
        $this->assertFilteredImageHasRatio(1/3);
        $this->assertFilteredImageWidthEqualsBoxWidth();
    }
    
    public function testFit_2x1_Image_in_2x1_Box()
    {
        $this->givenImageWithRatio(1/2);
        $this->givenBoxWithRatio(1/2);
        $this->givenFitStrategy();
        
        $this->whenFilter(); 
        
        $this->assertFilteredImageHasRatio(1/2);
        $this->assertFilteredImageWidthEqualsBoxWidth();
    }
    
    public function testFit_3x4_Image_in_1x2_Box()
    {
        $this->givenImageWithRatio(4/3);
        $this->givenBoxWithRatio(2/1);
        $this->givenFitStrategy();
        
        $this->whenFilter(); 
        
        $this->assertFilteredImageHasRatio(4/3);
        $this->assertFilteredImageWidthEqualsBoxWidth();
    }
}
