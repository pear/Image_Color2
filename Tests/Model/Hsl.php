<?php

/**
 * Image_Color2 Tests
 *
 * @version $Id$
 * @copyright 2005
 * @link        http://www.december.com/html/spec/colorsafecodes.html
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Image/Color2/Model/Hsl.php';

class Image_Color2_Tests_Model_Hsl extends PHPUnit2_Framework_TestCase {
    function testFromRgb() {
        $model = Image_Color2_Model_Hsl::fromRgb(array(128, 255, 128));
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Hsl, $model);

        $expected = '120, 50%, 75%';
        $this->assertEquals($expected, $model->getString());
    }

    function testFromArray() {
        $model = Image_Color2_Model_Hsl::fromArray(array(240, .50, .25));
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Hsl, $model);

        $expected = array(240, .50, .25, 'type'=>'hsl');
        $this->assertEquals($expected, $model->getArray());
    }

    function testFromString_Black() {
        $expected = '0, 0%, 0%';
        $model = Image_Color2_Model_Hsl::fromString($expected);
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Hsl, $model);
        $this->assertEquals($expected, $model->getString());
    }
    function testFromString_Colored() {
        $expected = '210, 20%, 90%';
        $model = Image_Color2_Model_Hsl::fromString($expected);
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Hsl, $model);
        $this->assertEquals($expected, $model->getString());
    }

    function testGetRgb_Black() {
        $model = Image_Color2_Model_Hsl::fromArray(array(0, .5, 0));
        $expected = array(0, 0, 0, 'type'=>'rgb');
        $this->assertEquals($expected, $model->getRgb());
    }
    function testGetRgb_Red() {
        $model = Image_Color2_Model_Hsl::fromArray(array(0, 1, .5));
        $expected = array(255, 0, 0, 'type'=>'rgb');
        $this->assertEquals($expected, $model->getRgb());
    }
    function testGetRgb_Blue() {
        $model = Image_Color2_Model_Hsl::fromArray(array(240, 1, .5));
        $expected = array(0, 0, 255, 'type'=>'rgb');
        $this->assertEquals($expected, $model->getRgb());
    }
    function testGetRgb_Greenish() {
        $model = Image_Color2_Model_Hsl::fromArray(array(120, .79, .52));
        $expected = array(36, 229, 36, 'type'=>'rgb');
        $this->assertEquals($expected, $model->getRgb());
    }

    function testGetArray() {
        $expected = array(120, 0.50, 0.75, 'type'=>'hsl');
        $model = Image_Color2_Model_Hsl::fromArray($expected);
        $this->assertEquals($expected, $model->getArray());
    }

    function testGetString() {
        $model = Image_Color2_Model_Hsl::fromArray(array(210, 0.28, 0.94));
        $expected = '210, 28%, 94%';
        $this->assertEquals($expected, $model->getString());
    }
}

?>
