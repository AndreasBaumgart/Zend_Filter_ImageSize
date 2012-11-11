<?php
// Copyright (c) 2009-2012 Andreas Baumgart
// 
// This source file is subject to the MIT license that is bundled with this 
// package in the file LICENSE.txt.

/**
 * An interface for transforming a input path to an output path according to
 * a given configuration. 
 */
interface Polycast_Filter_ImageSize_PathBuilder_Interface
{
    /**
     * Builds a new path from a given path according to the given configuration.
     * 
     * @param string $filename
     * @param Polycast_Filter_ImageSize_Configuration_Interface $config
     * @return string
     */
    public function buildPath($filename, Polycast_Filter_ImageSize_Configuration_Interface $config);
}
