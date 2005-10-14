<?php

/**
 * Image_Color2 Tests
 *
 * @version $Id$
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Image/Color2/Model/Named.php';

class Image_Color2_Tests_Model_Named extends PHPUnit2_Framework_TestCase {
    function testFromRgb() {
        $model = Image_Color2_Model_Named::fromRgb(array(255,255,255));
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Named, $model);
        $this->assertEquals('white', $model->getString());
    }

    function testFromArray() {
        $model = Image_Color2_Model_Named::fromArray(array('black', 'type'=>'named'));
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Named, $model);
        $this->assertEquals('black', $model->getString());
    }
    function testFromArray_Unknown() {
        try {
            $model = Image_Color2_Model_Named::fromArray(array('madeupcolor'));
        } catch (PEAR_Exception $ex) {
            return;
        }
        $this->fail('an exception should have been thrown.');
    }

    function testFromString() {
        $model = Image_Color2_Model_Named::fromString('black');
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Named, $model);
        $this->assertEquals('black', $model->getString());
    }
    function testFromString_UppercaseWithSpaces() {
        $model = Image_Color2_Model_Named::fromString('Light Steel BLUE');
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType(Image_Color2_Model_Named, $model);
        $this->assertEquals('lightsteelblue', $model->getString());
    }
    function testFromString_Unknown() {
        try {
            $model = Image_Color2_Model_Named::fromString('madeupcolor');
        } catch (PEAR_Exception $ex) {
            return;
        }
        $this->fail('an exception should have been thrown.');
    }

    function testGetRgb() {
        $model = Image_Color2_Model_Named::fromString('white');
        $this->assertEquals(array(255,255,255,'type'=>'rgb'), $model->getRgb());
    }

    function testGetArray() {
        $model = Image_Color2_Model_Named::fromString('black');
        $this->assertEquals(array('black', 'type'=>'named'), $model->getArray());
    }

    function testGetString() {
        $model = Image_Color2_Model_Named::fromString('black');
        $this->assertEquals('black', $model->getString());
    }
}

?>
