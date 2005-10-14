<?php

/**
 * Image_Color2 Tests
 *
 * @version $Id$
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Image/Color2/Model/Hex.php';

class Image_Color2_Tests_Model_Hex extends PHPUnit2_Framework_TestCase {
    function testFromRgb() {
        $model = Image_Color2_Model_Hex::fromRgb(array(171, 205, 239));
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Hex, $model);
        $this->assertEquals('#abcdef', $model->getString());
    }

    function testFromArray() {
        $model = Image_Color2_Model_Hex::fromArray(array(0xAB,0xcd,0xEF));
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Hex, $model);
        $this->assertEquals('#abcdef', $model->getString());
    }

    function testFromString() {
        $model = Image_Color2_Model_Hex::fromString('#abCDef');
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Hex, $model);
        $this->assertEquals('#abcdef', $model->getString());
    }
    function testFromString_Shortform() {
        $model = Image_Color2_Model_Hex::fromString('#abc');
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Hex, $model);
        $this->assertEquals('#aabbcc', $model->getString());
    }
    function testFromString_Black() {
        $model = Image_Color2_Model_Hex::fromString('#000000');
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Hex, $model);
        $this->assertEquals('#000000', $model->getString());
    }

    function testGetRgb() {
        $model = Image_Color2_Model_Hex::fromString('#abcdef');
        $this->assertEquals(array(171, 205, 239,'type'=>'rgb'), $model->getRgb());
    }

    function testGetArray() {
        $model = Image_Color2_Model_Hex::fromString('#abcdef');
        $this->assertEquals(array(0xab, 0xcd, 0xef,'type'=>'rgb'), $model->getArray());
    }

    function testGetString() {
        $model = Image_Color2_Model_Hex::fromString('#abcdef');
        $this->assertEquals('#abcdef', $model->getString());
    }
}

?>
