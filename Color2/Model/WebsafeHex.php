<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * WebsafeHex.php contains code for websafe hex RGB colors.
 *
 * PHP version 5
 *
 * @version     $Id$
 * @license     http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @category    Image
 * @package     Image_Color2
 * @subpackage  Model
 * @copyright   2005
 * @author      andrew morton <drewish@katherinehouse.com>
 */

/**
 * This class extends the Image_Color2_Model_Hex class.
 */
require_once 'Image/Color2/Model/Hex.php';

/**
 * Hex color model that is limited to websafe colors.
 *
 * @author      andrew morton <drewish@katherinehouse.com>
 * @category    Image
 * @package     Image_Color2
 * @subpackage  Model
 */
class Image_Color2_Model_WebsafeHex extends Image_Color2_Model_Hex {
    /**
     * Construct the websafe hex color from RGB components. All values will be
     * rounded to the closest websafe value.
     *
     * @param   integer $r Red 0-255
     * @param   integer $g Green 0-255
     * @param   integer $b Blue 0-255
     * @uses    round() to round to websafe values.
     */
    protected function __construct($r, $g, $b)
    {
        $this->_r = self::round((integer) $r);
        $this->_g = self::round((integer) $g);
        $this->_b = self::round((integer) $b);
    }

    /**
     * Round a RGB element to the closest websafe value.
     *
     * Invalid values--those less than 0 or greater than 255--are forced to the
     * minimum or maximum values respectively.
     *
     * @param   integer $int An RGB color component.
     * @return  integer A websafe RGB component (0x00, 0x33, 0x66, 0x99, 0xcc,
     *          or 0xff).
     */
    static public function round($int)
    {
        if ($int < 0x1a ) {
            return 0x00;
        } else if ( $int < 0x4d ) {
            return 0x33;
        } else if ( $int < 0x80 ) {
            return 0x66;
        } else if ( $int < 0xB3 ) {
            return 0x99;
        } else if ( $int < 0xE6 ) {
            return 0xCC;
        } else {
            return 0xFF;
        }
    }

    /**
     * {@inheritdoc}
     * @param   array $rgb A PEAR style RGB array containing three integers
     *          from 0 to 255 for the Red, Green, and Blue color components
     *          followed by a 'type' => 'rgb' element.
     * @return  Image_Color2_Model_WebsafeHex
     */
    static public function fromRgb($rgb)
    {
        return new self($rgb[0], $rgb[1], $rgb[2]);
    }

    /**
     * {@inheritdoc}
     * @param   array $array
     * @return  Image_Color2_Model_WebsafeHex
     */
    static public function fromArray($array)
    {
        return new self(
            hexdec($array[0]), hexdec($array[1]), hexdec($array[2])
        );
    }

    /**
     * {@inheritdoc}
     * @param   string $str In the format 'AABBCC', 'ABC', '#ABCDEF', or '#ABC'.
     * @return  Image_Color2_Model_WebsafeHex
     */
    static public function fromString($str)
    {
        $color = str_replace('#', '', $str);
        return new self(
            hexdec(substr($color, 0, 2)),
            hexdec(substr($color, 2, 2)),
            hexdec(substr($color, 4, 2))
        );
    }
}

?>
