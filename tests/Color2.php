<?php

/**
 * Image_Color2 Tests
 *
 * @version $Id$
 * @copyright 2005
 */

define('COLOR2_BASEDIR', realpath(dirname(__FILE__) . '/..'));

require_once 'PHPUnit/Framework.php';
require_once 'Image/Color2.php';
require_once 'Image/Color2/Model/Hex.php';
require_once 'Image/Color2/Model/Hsv.php';
require_once 'Image/Color2/Model/Named.php';

class Image_Color2_Tests_Color2 extends PHPUnit_Framework_TestCase {
    function testConstruct_FromArray_RgbWithoutType() {
        $color = new Image_Color2(array(0,0,0));
        $this->assertNotNull($color);
        $this->assertEquals(array(0,0,0,'type'=>'rgb'), $color->getRgb());
    }
    function testConstruct_FromArray_Rgb() {
        $color = new Image_Color2(array(1,2,3,'type'=>'rgb'));
        $this->assertNotNull($color);
        $this->assertEquals(array(1,2,3,'type'=>'rgb'), $color->getRgb());
    }
    function testConstruct_FromArray_Rgba() {
        $color = new Image_Color2(array(1,2,3,4,'type'=>'rgb'));
        $this->assertNotNull($color);
        $this->assertEquals(array(1,2,3,4,'type'=>'rgb'), $color->getRgb());
    }

    function testConstruct_FromArray_Hex() {
        $color = new Image_Color2(array(0xff,0xff, 0xff,'type'=>'hex'));
        $this->assertNotNull($color);
        $this->assertEquals(array(255,255,255,'type'=>'rgb'), $color->getRgb());
    }
    function testConstruct_FromArray_Hsv() {
        $color = new Image_Color2(array(210, 0.28, 0.94, 'type'=>'hsv'));
        $this->assertNotNull($color);
        $this->assertEquals(array(173, 206, 240, 'type'=>'rgb'), $color->getRgb());
    }
    function testConstruct_FromArray_InvalidType() {
        $old = error_reporting(error_reporting()^E_WARNING);
        try {
            $color = new Image_Color2(array(1, 'type'=>'badtype'));
        } catch (PEAR_Exception $ex) {
            error_reporting($old);
            return;
        }
        error_reporting($old);
        $this->fail('an exception should have been thrown.');
    }

    function testConstruct_FromString_Hex() {
        $color = new Image_Color2('#FFFFFF');
        $this->assertNotNull($color);
        $this->assertEquals(array(255,255,255,'type'=>'rgb'), $color->getRgb());
    }
    function testConstruct_FromString_Named() {
        $color = new Image_Color2('white');
        $this->assertNotNull($color);
        $this->assertEquals(array(255,255,255,'type'=>'rgb'), $color->getRgb());
    }
    function testConstruct_FromString_NamedInvalid() {
        try {
            $color = new Image_Color2('madeupcolor');
        } catch (PEAR_Exception $ex) {
            return;
        }
        $this->fail('an exception should have been thrown.');
    }

    function testConstruct_FromColorModel_Named() {
        $model = Image_Color2_Model_Named::fromString('teal');
        $color = new Image_Color2($model);
        $this->assertNotNull($color);
        $this->assertEquals(array(0, 128, 128, 'type'=>'rgb'), $color->getRgb());
    }
    function testConstruct_FromColorModel_Hsv() {
        $model = Image_Color2_Model_Hsv::fromArray(array(210, 0.28, 0.94, 'type'=>'hsv'));
        $color = new Image_Color2($model);
        $this->assertNotNull($color);
        $this->assertEquals(array(173, 206, 240, 'type'=>'rgb'), $color->getRgb());
    }
    function testConstruct_FromColorModel_Bad() {
        try {
            $color = new Image_Color2(null);
        } catch (PEAR_Exception $ex) {
            return;
        }
        $this->fail('an exception should have been thrown.');
    }

    function testGetHex() {
        $color = new Image_Color2('white');
        $result = $color->getHex();
        $this->assertType('string', $result);
        $this->assertEquals('#ffffff', $result);
    }

    function testGetRgb() {
        $color = new Image_Color2(array(0,128,255));
        $result = $color->getRgb();
        $this->assertType('array', $result);
        $this->assertEquals(array(0,128,255,'type'=>'rgb'), $result);
    }
    function testGetRgb_UseIndex() {
        $color = new Image_Color2(array(0,128,255));
        $result = $color->getRgb(0);
        $this->assertEquals(0, $result);
        $result = $color->getRgb(1);
        $this->assertEquals(128, $result);
        $result = $color->getRgb(2);
        $this->assertEquals(255, $result);
        $result = $color->getRgb('type');
        $this->assertEquals('rgb', $result);
    }


    function testGetArray_Rgb() {
        $color = new Image_Color2(array(0,128,255));
        $result = $color->getArray();
        $this->assertType('array', $result);
        $this->assertEquals(array(0,128,255,'type'=>'rgb'), $result);
    }
    function testGetArray_Hex() {
        $color = new Image_Color2(array(0x0, 0x77, 0xFF,'type'=>'hex'));
        $result = $color->getArray();
        $this->assertType('array', $result);
        $this->assertEquals(array(0x0, 0x77, 0xFF,'type'=>'rgb'), $result);
    }
    function testGetArray_Hsv() {
        $color = new Image_Color2(array(210, 0.28, 0.94, 'type'=>'hsv'));
        $result = $color->getArray();
        $this->assertType('array', $result);
        $this->assertEquals(array(210, 0.28, 0.94, 'type'=>'hsv'), $result);
    }
    function testGetArray_UseIndex() {
        $color = new Image_Color2(array(1,128,255));
        $result = $color->getArray(0);
        $this->assertEquals(1, $result);
        $result = $color->getArray(1);
        $this->assertEquals(128, $result);
        $result = $color->getArray(2);
        $this->assertEquals(255, $result);
        $result = $color->getArray('type');
        $this->assertEquals('rgb', $result);
    }


    function testConvertTo_NamedToHex() {
        $color = new Image_Color2('orange');
        $color2 = $color->convertTo('hex');
        $this->assertType('Image_Color2', $color2);
        $result = $color2->getString();
        $this->assertEquals('#ffa500', $result);
    }
    function testConvertTo_RgbToNamed() {
        $color = new Image_Color2(array(128,128,128));
        $color2 = $color->convertTo('named');
        $this->assertType('Image_Color2', $color2);
        $result = $color2->getString();
        $this->assertEquals('gray', $result);
    }


    function testGetString_Rgb() {
        $color = new Image_Color2(array(0,128,255));
        $result = $color->getString();
        $this->assertEquals('#0080ff', $result);
    }
    function testGetString_Named() {
        $color = new Image_Color2('orange');
        $result = $color->getString();
        $this->assertEquals('orange', $result);
    }
    function testGetString_Hex() {
        $color = new Image_Color2('#abcdef');
        $result = $color->getString();
        $this->assertEquals('#abcdef', $result);
    }


    function testAverage_Rgb() {
        $red = new Image_Color2('red');
        $blue = new Image_Color2('blue');
        $result = Image_Color2::average($red, $blue);
        $this->assertType('Image_Color2', $result);
        $this->assertEquals('#800080', $result->getHex());
        $this->assertEquals('purple', $result->convertTo('named')->getString());
    }

    function testAverage_Rgba() {
        $red = new Image_Color2(array(255,0,0,255));
        $blue = new Image_Color2(array(0,0,255,0));
        $result = Image_Color2::average($red, $blue);
        $this->assertType('Image_Color2', $result);
        $this->assertEquals(array(128,0,128,128,'type'=>'rgb'), $result->getArray());
    }

}

?>
