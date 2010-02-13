<?php

/**
 * Image_Color2 Tests
 *
 * @version $Id$
 * @copyright 2005
 */

require_once 'PHPUnit/Framework.php';
require_once 'Image/Color2/Model/WebsafeHex.php';

class Image_Color2_Tests_Model_WebsafeHex extends PHPUnit_Framework_TestCase {
    function testFromRgb() {
        $model = Image_Color2_Model_WebsafeHex::fromRgb(array(171, 205, 239));
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType('Image_Color2_Model_WebsafeHex', $model);
        $this->assertEquals('#99ccff', $model->getString());
    }

    function testFromArray() {
        $model = Image_Color2_Model_WebsafeHex::fromArray(array('AB','cd','EF'));
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType('Image_Color2_Model_WebsafeHex', $model);
        $this->assertEquals('#99ccff', $model->getString());
    }

    function testFromString() {
        $model = Image_Color2_Model_WebsafeHex::fromString('#abCDef');
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType('Image_Color2_Model_WebsafeHex', $model);
        $this->assertEquals('#99ccff', $model->getString());
    }
    function testFromString_Black() {
        $model = Image_Color2_Model_WebsafeHex::fromString('#000000');
        $this->assertTrue($model instanceof Image_Color2_Model);
        $this->assertType('Image_Color2_Model_WebsafeHex', $model);
        $this->assertEquals('#000000', $model->getString());
    }


    function testRound_00() {
        $this->assertEquals(0x00, Image_Color2_Model_WebsafeHex::round(-1));
        $this->assertEquals(0x00, Image_Color2_Model_WebsafeHex::round(0));
        $this->assertEquals(0x00, Image_Color2_Model_WebsafeHex::round(25));
    }
    function testRound_33() {
        $this->assertEquals(0x33, Image_Color2_Model_WebsafeHex::round(26));
        $this->assertEquals(0x33, Image_Color2_Model_WebsafeHex::round(76));
    }
    function testRound_66() {
        $this->assertEquals(0x66, Image_Color2_Model_WebsafeHex::round(77));
        $this->assertEquals(0x66, Image_Color2_Model_WebsafeHex::round(127));
    }
    function testRound_99() {
        $this->assertEquals(0x99, Image_Color2_Model_WebsafeHex::round(128));
        $this->assertEquals(0x99, Image_Color2_Model_WebsafeHex::round(178));
    }
    function testRound_cc() {
        $this->assertEquals(0xcc, Image_Color2_Model_WebsafeHex::round(179));
        $this->assertEquals(0xcc, Image_Color2_Model_WebsafeHex::round(229));
    }
    function testRound_ff() {
        $this->assertEquals(0xff, Image_Color2_Model_WebsafeHex::round(230));
        $this->assertEquals(0xff, Image_Color2_Model_WebsafeHex::round(255));
        $this->assertEquals(0xFF, Image_Color2_Model_WebsafeHex::round(257));
    }

    function testGetRgb() {
        $model = Image_Color2_Model_WebsafeHex::fromString('#abcdef');
        $this->assertEquals(array(153, 204, 255,'type'=>'rgb'), $model->getRgb());
    }

    function testGetArray() {
        $model = Image_Color2_Model_WebsafeHex::fromString('#abcdef');
        $this->assertEquals(array(0x99, 0xcc, 0xff,'type'=>'rgb'), $model->getArray());
    }

    function testGetString() {
        $model = Image_Color2_Model_WebsafeHex::fromString('#abcdef');
        $this->assertEquals('#99ccff', $model->getString());
    }
}

?>
