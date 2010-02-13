<?php

/**
 * Runner for all ColorModel tests.
 *
 * @version $Id$
 * @copyright 2005
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Image_Color2_Tests_Model_AllTests::main');
}

require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__) . '/Cmyk.php';
require_once dirname(__FILE__) . '/Grayscale.php';
require_once dirname(__FILE__) . '/Hex.php';
require_once dirname(__FILE__) . '/Hsl.php';
require_once dirname(__FILE__) . '/Hsv.php';
require_once dirname(__FILE__) . '/Named.php';
require_once dirname(__FILE__) . '/WebsafeHex.php';

class Image_Color2_Tests_Model_AllTests {
    public static function main() {
        require_once 'PHPUnit/TextUI/TestRunner.php';
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('ColorModel Tests');

        $suite->addTestSuite('Image_Color2_Tests_Model_Cmyk');
        $suite->addTestSuite('Image_Color2_Tests_Model_Grayscale');
        $suite->addTestSuite('Image_Color2_Tests_Model_Hex');
        $suite->addTestSuite('Image_Color2_Tests_Model_Hsl');
        $suite->addTestSuite('Image_Color2_Tests_Model_Hsv');
        $suite->addTestSuite('Image_Color2_Tests_Model_Named');
        $suite->addTestSuite('Image_Color2_Tests_Model_WebsafeHex');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Image_Color2_Tests_Model_AllTests::main') {
    Image_Color2_Tests_Model_AllTests::main();
}


?>
