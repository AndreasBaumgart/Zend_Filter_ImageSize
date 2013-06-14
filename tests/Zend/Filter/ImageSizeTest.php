<?php

class Zend_Filter_ImageSizeTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $this->assertInstanceOf('Zend_Filter_ImageSize', new Zend_Filter_ImageSize(array()));
    }
}
