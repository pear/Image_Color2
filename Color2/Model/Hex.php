<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Hex.php contains code for representing RGB colors as hex strings.
 *
 * PHP version 5
 *
 * @category    Image
 * @package     Image_Color2
 * @subpackage  Model
 * @author      andrew morton <drewish@katherinehouse.com>
 * @copyright   2005
 * @license     http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @version     $Id$
 * @link        http://pear.php.net/package/Image_Color2
 */

/**
 * This class implements the Image_Color2_Model interface.
 */
require_once 'Image/Color2/Model.php';

/**
 * Hex color model defines RGB colors using hexadecimal values for each
 * component.
 *
 * Typically, these values are concatenated into a string and
 * prefixed by the # character:
 * <pre>#339900
 * #390</pre>
 *
 * @category    Image
 * @package     Image_Color2
 * @subpackage  Model
 * @author      andrew morton <drewish@katherinehouse.com>
 * @copyright   2005
 * @license     http://opensource.org/licenses/lgpl-license.php
 *              GNU Lesser General Public License, Version 2.1
 * @link        http://pear.php.net/package/Image_Color2
 */
class Image_Color2_Model_Hex implements Image_Color2_Model {
    /**
     * Red component
     * @var integer 0-255
     */
    protected $_r = 0;
    /**
     * Green component
     * @var integer 0-255
     */
    protected $_g = 0;
    /**
     * Blue component
     * @var integer 0-255
     */
    protected $_b = 0;

    /**
     * Construct the hex color from RGB components.
     *
     * @param   integer $r 0-255
     * @param   integer $g 0-255
     * @param   integer $b 0-255
     */
    protected function __construct($r, $g, $b)
    {
        $this->_r = (integer) $r;
        $this->_g = (integer) $g;
        $this->_b = (integer) $b;
    }

    /**
     * Pass the RGB components to the constructor and return a new instance of
     * Image_Color2_Model.
     * @param   array $rgb A PEAR style RGB array containing three integers
     *          from 0 to 255 for the Red, Green, and Blue color components
     *          followed by a 'type' => 'rgb' element.
     * @return  Image_Color2_Model_Hex
     */
    static public function fromRgb($rgb)
    {
        return new self($rgb[0], $rgb[1], $rgb[2]);
    }

    /**
     * Construct a new Image_Color2_Model from the components in an array. The
     * array format happens to be the PEAR style RGB array so we just hand it
     * off to {@link fromRgb}.
     *
     * @param   array $rgb A PEAR style RGB array containing three integers
     *          from 0 to 255 for the Red, Green, and Blue color components
     *          followed by a 'type' => 'rgb' element.
     * @return  Image_Color2_Model_Hex
     * @uses    fromRgb() becase both functions share the exact same parameter
     *          format.
     */
    static public function fromArray($array)
    {
        return self::fromRgb($array);
    }

    /**
     * Convert the hex string into RGB components and return a new instance of
     * Image_Color2_Model. Allow both #abc and #aabbcc forms.
     * @param   string $str in the format 'AABBCC', 'ABC', '#ABCDEF', or '#ABC'
     * @return  Image_Color2_Model_Hex
     */
    static public function fromString($str)
    {
        $color = str_replace('#', '', $str);
        if (strlen($color) == 3) {
            // short #abc form
               return new self(
                hexdec($color{0} . $color{0}),
                hexdec($color{1} . $color{1}),
                hexdec($color{2} . $color{2})
            );
        } else {
            // long #aabbcc form
            return new self(
                hexdec(substr($color, 0, 2)),
                hexdec(substr($color, 2, 2)),
                hexdec(substr($color, 4, 2))
            );
        }
    }

    /**
     * {@inheritdoc}
     * @return  array A PEAR style RGB array containing three integers
     *          from 0 to 255 for the Red, Green, and Blue color components
     *          followed by a 'type' => 'rgb' element.
     */
    public function getRgb()
    {
        return array($this->_r, $this->_g, $this->_b, 'type' => 'rgb');
    }

    /**
     * {@inheritdoc}
     * @return  array A PEAR style RGB array containing three integers
     *          from 0 to 255 for the Red, Green, and Blue color components
     *          followed by a 'type'=>'rgb' element.
     * @uses    getRgb() because both functions share the same return format.
     */
    public function getArray()
    {
        return $this->getRgb();
    }

    /**
     * {@inheritdoc}
     * @return  string A string in the format '#RRGGBB' where each channel is
     *          a hex byte.
     */
    public function getString()
    {
        return sprintf('#%02x%02x%02x', $this->_r, $this->_g, $this->_b);
    }
}

?>
