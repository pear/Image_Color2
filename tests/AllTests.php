<?php

/**
 * Runner for all ColorModel tests.
 *
 * @version $Id$
 * @copyright 2005
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Image_Color2_Tests_AllTests::main');
}

require_once 'PHPUnit/Framework.php';

set_include_path(realpath('../') . PATH_SEPARATOR . get_include_path());

require_once 'tests/Color2.php';
require_once 'tests/Model/AllTests.php';



class Image_Color2_Tests_AllTests
{
    public static function main() {
        require_once 'PHPUnit/TextUI/TestRunner.php';
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('Color2 Tests');

        $suite->addTestSuite('Image_Color2_Tests_Color2');
        $suite->addTest(Image_Color2_Tests_Model_AllTests::suite());

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Image_Color2_Tests_AllTests::main') {
    Image_Color2_Tests_AllTests::main();
}

?>
