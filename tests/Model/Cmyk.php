<?php

/**
 * Image_Color2 Tests
 *
 * @version     $Id$
 * @copyright   2005
 * @link        http://www.december.com/html/spec/colorsafecodes.html
 */

require_once 'PHPUnit/Framework.php';
require_once 'Image/Color2/Model/Cmyk.php';

class Image_Color2_Tests_Model_Cmyk extends PHPUnit_Framework_TestCase {
    function testFromRgb_CCFF00() {
        $expected = '20%, 0%, 100%, 0%';
        $model = Image_Color2_Model_Cmyk::fromRgb(array(204, 255, 0));
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType('Image_Color2_Model_Cmyk', $model);
        $this->assertEquals($expected, $model->getString());
    }
    function testFromRgb_9900CC() {
        $expected = '25%, 100%, 0%, 20%';
        $model = Image_Color2_Model_Cmyk::fromRgb(array(153, 0, 204));
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType('Image_Color2_Model_Cmyk', $model);
        $this->assertEquals($expected, $model->getString());
    }
    function testFromRgb_FF9933() {
        $expected = '0%, 40%, 80%, 0%';
        $model = Image_Color2_Model_Cmyk::fromRgb(array(255, 153, 51));
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType('Image_Color2_Model_Cmyk', $model);
        $this->assertEquals($expected, $model->getString());
    }


    function testFromArray_Color() {
        $expected = array(0.99, 0.10, 0.25, 0.50, 'type'=>'cmyk');
        $model = Image_Color2_Model_Cmyk::fromArray(array(.99, .1, .25, .5));
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType('Image_Color2_Model_Cmyk', $model);
        $this->assertEquals($expected, $model->getArray());
    }
    function testFromArray_Black() {
        $expected = array(.0, .0, .0, 1.0, 'type'=>'cmyk');
        $model = Image_Color2_Model_Cmyk::fromArray(array(.99, .1, .25, 1));
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType('Image_Color2_Model_Cmyk', $model);
        $this->assertEquals($expected, $model->getArray());
    }


    function testFromString_Percents_Black() {
        $expected = '0%, 0%, 0%, 100%';
        $model = Image_Color2_Model_Cmyk::fromString('0% 33% 80% 100%');
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType('Image_Color2_Model_Cmyk', $model);
        $this->assertEquals($expected, $model->getString());
    }
    function testFromString_Percent_NotBlack() {
        $expected = '100%, 33%, 80%, 0%';
        $model = Image_Color2_Model_Cmyk::fromString($expected);
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType('Image_Color2_Model_Cmyk', $model);
        $this->assertEquals($expected, $model->getString());
    }
    function testFromString_Floats_Black() {
        $expected = '0%, 0%, 0%, 100%';
        $model = Image_Color2_Model_Cmyk::fromString('0% 33% 80% 100%');
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType('Image_Color2_Model_Cmyk', $model);
        $this->assertEquals($expected, $model->getString());
    }
    function testFromString_Floats_NotBlack() {
        $expected = '100%, 33%, 80%, 0%';
        $model = Image_Color2_Model_Cmyk::fromString('1 .33 .8 0');
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType('Image_Color2_Model_Cmyk', $model);
        $this->assertEquals($expected, $model->getString());
    }


    function testGetRgb_Black() {
        $expected = array(0, 0, 0, 'type'=>'rgb');
        $model = Image_Color2_Model_Cmyk::fromArray(array(0, 0.33, 0.80, 1));
        $this->assertEquals($expected, $model->getRgb());
    }
    function testGetRgb_FF3366() {
        $expected = array(255, 51, 102, 'type'=>'rgb');
        $model = Image_Color2_Model_Cmyk::fromArray(array(0, 0.80, 0.60, 0));
        $this->assertEquals($expected, $model->getRgb());
    }
    function testGetRgb_3366CC() {
        $expected = array(51, 102, 204, 'type'=>'rgb');
        $model = Image_Color2_Model_Cmyk::fromArray(array(0.75, 0.50, 0, 0.20));
        $this->assertEquals($expected, $model->getRgb());
    }


    function testGetArray() {
        $expected = array(.1, .2, .8, .9, 'type'=>'cmyk');
        $model = Image_Color2_Model_Cmyk::fromArray($expected);
        $this->assertEquals($expected, $model->getArray());
    }


    function testGetString_Black() {
        $expected = '0%, 0%, 0%, 100%';
        $model = Image_Color2_Model_Cmyk::fromArray(array(0, 0.33, 0.8, 1));
        $this->assertEquals($expected, $model->getString());
    }
    function testGetString_NotBlack() {
        $expected = '100%, 33%, 80%, 0%';
        $model = Image_Color2_Model_Cmyk::fromArray(array(1, 0.33, 0.8, 0));
        $this->assertEquals($expected, $model->getString());
    }
}

?>
