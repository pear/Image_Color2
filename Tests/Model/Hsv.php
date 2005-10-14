<?php

/**
 * Image_Color2 Tests
 *
 * @version $Id$
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Image/Color2/Model/Hsv.php';

class Image_Color2_Tests_Model_Hsv extends PHPUnit2_Framework_TestCase {
    function testFromRgb_ProperType() {
        $model = Image_Color2_Model_Hsv::fromRgb(array(171, 205, 239));
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Hsv, $model);
    }

    function testFromRgb_Blueish() {
        $model = Image_Color2_Model_Hsv::fromRgb(array(171, 205, 239));
        $expected = '210, 28%, 94%';
        $actual = $model->getString();
        $this->assertEquals($expected, $actual);
    }
    function testFromRgb_Yellowish() {
        $model = Image_Color2_Model_Hsv::fromRgb(array(192, 175, 96));
        $expected = '49, 50%, 75%';
        $actual = $model->getString();
        $this->assertEquals($expected, $actual);
    }
    function testFromRgb_Black() {
        $model = Image_Color2_Model_Hsv::fromRgb(array(0, 0, 0));
        $expected = '0, 0%, 0%';
        $actual = $model->getString();
        $this->assertEquals($expected, $actual);
    }
    function testFromRgb_Gray() {
        $model = Image_Color2_Model_Hsv::fromRgb(array(128, 128, 128));
        $expected = '0, 0%, 50%';
        $actual = $model->getString();
        $this->assertEquals($expected, $actual);
    }
    function testFromRgb_White() {
        $model = Image_Color2_Model_Hsv::fromRgb(array(255, 255, 255));
        $expected = '0, 0%, 100%';
        $actual = $model->getString();
        $this->assertEquals($expected, $actual);
    }

    function testFromArray() {
        $model = Image_Color2_Model_Hsv::fromArray(array(210, 0.28, 0.94));
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Hsv, $model);
        $this->assertEquals('210, 28%, 94%', $model->getString());
    }
    function testFromArray_Gray() {
        $model = Image_Color2_Model_Hsv::fromArray(array(0, 0, 0.5));
        $expected = '0, 0%, 50%';
        $actual = $model->getString();
        $this->assertEquals($expected, $actual);
    }
    function testFromArray_White() {
        $model = Image_Color2_Model_Hsv::fromArray(array(0, 0, 1));
        $expected = '0, 0%, 100%';
        $actual = $model->getString();
        $this->assertEquals($expected, $actual);
    }

    function testFromString_Percents() {
        $model = Image_Color2_Model_Hsv::fromString('210, 2%, 9%');
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Hsv, $model);
        $this->assertEquals('210, 2%, 9%', $model->getString());
    }
    function testFromString_Floats() {
        $model = Image_Color2_Model_Hsv::fromString('210, .02, .09');
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Hsv, $model);
        $this->assertEquals('210, 2%, 9%', $model->getString());
    }

    function testGetRgb_Blueish() {
        $model = Image_Color2_Model_Hsv::fromArray(array(210, 0.28, 0.94));
        $expected = array(173, 206, 240, 'type'=>'rgb');
        $actual = $model->getRgb();
        $this->assertEquals($expected, $actual);
    }
    function testGetRgb_Black() {
        $model = Image_Color2_Model_Hsv::fromArray(array(0, 0, 0));
        $expected = array(0, 0, 0, 'type'=>'rgb');
        $actual = $model->getRgb();
        $this->assertEquals($expected, $actual);
    }
    function testGetRgb_Gray() {
        $model = Image_Color2_Model_Hsv::fromArray(array(0, 0, 0.5));
        $expected = array(128, 128, 128, 'type'=>'rgb');
        $actual = $model->getRgb();
        $this->assertEquals($expected, $actual);
    }
    function testGetRgb_White() {
        $model = Image_Color2_Model_Hsv::fromArray(array(0, 0, 1));
        $expected = array(255, 255, 255, 'type'=>'rgb');
        $actual = $model->getRgb();
        $this->assertEquals($expected, $actual);
    }

    function testGetArray() {
        $model = Image_Color2_Model_Hsv::fromArray(array(210, 0.28, 0.94));
        $this->assertEquals(array(210, 0.28, 0.94, 'type'=>'hsv'), $model->getArray());
    }

    function testGetString() {
        $model = Image_Color2_Model_Hsv::fromArray(array(210, 0.28, 0.94));
        $this->assertEquals('210, 28%, 94%', $model->getString());
    }
}

?>
