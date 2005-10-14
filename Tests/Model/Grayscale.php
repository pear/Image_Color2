<?php

/**
 * Image_Color2 Tests
 *
 * @version $Id$
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Image/Color2/Model/Grayscale.php';

class Image_Color2_Tests_Model_Grayscale extends PHPUnit2_Framework_TestCase {
    function testFromRgb_Black() {
        $model = Image_Color2_Model_Grayscale::fromRgb(array(0, 0, 0));
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Grayscale, $model);
        $this->assertEquals('0%', $model->getString());
    }
    function testFromRgb_Color() {
        $model = Image_Color2_Model_Grayscale::fromRgb(array(171, 205, 239));
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Grayscale, $model);
        $this->assertEquals('77.86%', $model->getString());
    }
    function testFromRgb_Gray() {
        $model = Image_Color2_Model_Grayscale::fromRgb(array(128, 128, 128));
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Grayscale, $model);
        $this->assertEquals('50.2%', $model->getString());
    }


    function testFromArray_Float() {
        $model = Image_Color2_Model_Grayscale::fromArray(array(0.1235));
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Grayscale, $model);
        $this->assertEquals('12.35%', $model->getString());
    }


    function testFromString_Float_TooSmall() {
        $model = Image_Color2_Model_Grayscale::fromString('-1');
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Grayscale, $model);
        $this->assertEquals('0%', $model->getString());
    }
    function testFromString_Float_TooBig() {
        $model = Image_Color2_Model_Grayscale::fromString('1.1');
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Grayscale, $model);
        $this->assertEquals('100%', $model->getString());
    }
    function testFromString_Float_White() {
        $model = Image_Color2_Model_Grayscale::fromString('1');
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Grayscale, $model);
        $this->assertEquals('100%', $model->getString());
    }
    function testFromString_Float_75Gray() {
        $model = Image_Color2_Model_Grayscale::fromString('0.75');
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Grayscale, $model);
        $this->assertEquals('75%', $model->getString());
    }
    function testFromString_Float_Black() {
        $model = Image_Color2_Model_Grayscale::fromString('.0');
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Grayscale, $model);
        $this->assertEquals('0%', $model->getString());
    }

    function testFromString_Percent_White() {
        $model = Image_Color2_Model_Grayscale::fromString('101%');
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Grayscale, $model);
        $this->assertEquals('100%', $model->getString());
    }
    function testFromString_Percent_75Gray() {
        $model = Image_Color2_Model_Grayscale::fromString('75%');
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Grayscale, $model);
        $this->assertEquals('75%', $model->getString());
    }
    function testFromString_Percent_Black() {
        $model = Image_Color2_Model_Grayscale::fromString('-1%');
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Grayscale, $model);
        $this->assertEquals('0%', $model->getString());
    }


    function testGetRgb() {
        $model = Image_Color2_Model_Grayscale::fromString('50%');
        $this->assertEquals(array(128, 128, 128,'type'=>'rgb'), $model->getRgb());
    }


    function testGetArray_FromString() {
        $model = Image_Color2_Model_Grayscale::fromString('99.99%');
        $this->assertEquals(array(0.9999,'type'=>'Grayscale'), $model->getArray());
    }
    function testGetArray_FromFloat() {
        $model = Image_Color2_Model_Grayscale::fromArray(array(3/7));
        $this->assertEquals(array(round(3/7, 8),'type'=>'Grayscale'), $model->getArray());
    }


    function testGetString() {
        $model = Image_Color2_Model_Grayscale::fromString('12.3456%');
        $this->assertEquals('12.35%', $model->getString());
    }
    function testGetString_FromFloat() {
        $model = Image_Color2_Model_Grayscale::fromArray(array(2/3));
        $this->assertEquals('66.67%', $model->getString());
    }
}

?>
