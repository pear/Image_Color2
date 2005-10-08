<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Grayscale.php contains code for converting RGB to grayscale.
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
 * Grayscale color model converts RGB values to gray scale using perceptive
 * proportional sum of the RGB channels.
 *
 * The formula was taken, indirectly, from the GIMP:
 * "The formula used in the GIMP is Y = 0.3R + 0.59G + 0.11B; this result is
 * known as luminance. The weights used to compute luminance are related to the
 * monitor's phosphors. The explanation for these weights is due to the fact
 * that for equal amounts of color the eye is most sensitive to green, then
 * red, and then blue. This means that for equal amounts of green and blue
 * light the green will, nevertheless, seem much brighter".
 * {@link http://gimp-savvy.com/BOOK/index.html?node54.html Gimp-Savvy.com}
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
class Image_Color2_Model_Grayscale implements Image_Color2_Model {
    /**
     * Grayscale component
     * @var float 0.0 - 1.0
     */
    protected $_val = 0;

    const WEIGHT_RED = 0.3;
    const WEIGHT_GREEN = 0.59;
    const WEIGHT_BLUE = 0.11;

    /**
     * Construct the Grayscale color from RGB components.
     *
     * @param   float   0.0 - 1.0
     */
    protected function __construct($val)
    {
        if ($val <= 0.0) {
            $this->_val = 0.0;
        } else if ($val <= 1.0) {
            $this->_val = (float) $val;
        } else {
            $this->_val = 1.0;
        }
    }

    /**
     * Pass the RGB components to the constructor and return a new instance of
     * Image_Color2_Model.
     * @param   array $rgb A PEAR style RGB array containing three integers
     *          from 0 to 255 for the Red, Green, and Blue color components
     *          followed by a 'type' => 'rgb' element.
     * @return  Image_Color2_Model_Grayscale
     */
    static public function fromRgb($rgb)
    {
        // if all three values are the same, it's already grayscale.
        if ($rgb[0] == $rgb[1] && $rgb[1] == $rgb[2]) {
            $val = $rgb[0] / 255;
        } else {
            $val  = ($rgb[0] / 255) * self::WEIGHT_RED;
            $val += ($rgb[1] / 255) * self::WEIGHT_GREEN;
            $val += ($rgb[2] / 255) * self::WEIGHT_BLUE;
        }
        return new self($val);
    }

    /**
     * Pull the grayscale component out of the array and return a instance of
     * Image_Color2_Model.
     * @param   array $array An array with a floating point gray scale
     *          component.
     * @return  Image_Color2_Model_Grayscale
     */
    static public function fromArray($array)
    {
        return new self($array[0]);
    }

    /**
     * Convert the Grayscale string into RGB components and return a new
     * instance of Image_Color2_Model.
     * @param   string $str Grayscale expressed as a floating point value from
     *          zero to one ('0.005', '.3333', '1') or percent string ('0.5%',
     *          '33.33%', '100%').
     * @return  Image_Color2_Model_Grayscale
     */
    static public function fromString($str)
    {
        // drop any trailing spaces
        $str = trim($str);
        // convert it to a float
        $val = (float) $str;
        // if it was a percentage, divide by 100
        if (substr($str, -1) == '%') {
            $val /= 100;
        }
        return new self($val);
    }

    /**
     * {@inheritdoc}
     * @return  array A PEAR style RGB array containing three integers
     *          from 0 to 255 for the Red, Green, and Blue color components
     *          followed by a 'type' => 'rgb' element.
     */
    public function getRgb()
    {
        $i = (integer) (($this->_val * 255) + 0.5);
        return array($i, $i, $i, 'type' => 'rgb');
    }

    /**
     * {@inheritdoc}
     * @return  array An array with two elements, the first is the color, a
     *          float from 0 to 1 and the second type => named element.
     */
    public function getArray()
    {
        return array(round($this->_val, 8), 'type' => 'Grayscale');
    }

    /**
     * {@inheritdoc}
     * @return  string
     */
    public function getString()
    {
        return round($this->_val * 100, 2) . '%';
    }
}

?>
