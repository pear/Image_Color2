<?php

/**
 * Runner for all ColorModel tests.
 *
 * @version $Id$
 * @copyright 2005
 */

if (!defined('PHPUnit2_MAIN_METHOD')) {
    define('PHPUnit2_MAIN_METHOD', 'Image_Color2_Tests_AllTests::main');
}

require_once 'PHPUnit2/Framework/TestSuite.php';
require_once 'PHPUnit2/TextUI/TestRunner.php';
require_once 'PHPUnit2/HtmlUI/TestRunner.php';

set_include_path(realpath('../') . PATH_SEPARATOR . get_include_path());

require_once 'Tests/Color2.php';
require_once 'Tests/Model/AllTests.php';



class Image_Color2_Tests_AllTests {
    public static function main() {
        if (php_sapi_name() == 'cli') {
            PHPUnit2_TextUI_TestRunner::run(self::suite());
        } else {
            PHPUnit2_HtmlUI_TestRunner::run(self::suite());
        }
    }

    public static function suite() {
        $suite = new PHPUnit2_Framework_TestSuite('Color2 Tests');

        $suite->addTestSuite('Image_Color2_Tests_Color2');
        $suite->addTest(Image_Color2_Tests_Model_AllTests::suite());

        return $suite;
    }
}

if (PHPUnit2_MAIN_METHOD == 'Image_Color2_Tests_AllTests::main') {
    Image_Color2_Tests_AllTests::main();
}

?>
