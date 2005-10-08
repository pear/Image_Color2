<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Hsv.php contains code for converting between the RGB and HSL color models.
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
 * HSV (also called HSB) color model defines colors in terms of Hue,
 * Saturation, and Value (or Brightness).
 *
 * @category    Image
 * @package     Image_Color2
 * @subpackage  Model
 * @author      andrew morton <drewish@katherinehouse.com>
 * @copyright   2005
 * @license     http://opensource.org/licenses/lgpl-license.php
 *              GNU Lesser General Public License, Version 2.1
 * @link        http://pear.php.net/package/Image_Color2
 * @link        http://en.wikipedia.org/wiki/HSV_color_space
 */
class Image_Color2_Model_Hsv implements Image_Color2_Model {
    /**
     * Hue component
     * @var integer 0-360
    */
    protected $_h;
    /**
     * Saturation component
     * @var float 0.0-1.0
    */
    protected $_s;
    /**
     * Value component
     * @var float 0.0-1.0
    */
    protected $_v;

    /**
     * Construct from HSV components.
     *
     * @param   integer $h 0 - 360
     * @param   float   $s 0.0 - 1.0
     * @param   float   $v 0.0 - 1.0
     */
    protected function __construct($h, $s, $v)
    {
        $this->_h = (integer) $h;
        $this->_s = (float) $s;
        $this->_v = (float) $v;
    }

    /**
     * Convert the RGB array into HSV components and return a new Model
     * class.
     * @param   array $rgb A PEAR style RGB array containing three integers
     *          from 0 to 255 for the Red, Green, and Blue color components
     *          followed by a 'type' => 'rgb' element.
     * @return  Image_Color2_Model_Hsv
     */
    static public function fromRgb($rgb)
    {
        $r = $rgb[0] / 255;
        $g = $rgb[1] / 255;
        $b = $rgb[2] / 255;

        $min = min($r, $g, $b);
        $max = max($r, $g, $b);

        switch ($max) {
        case 0: // it's black like my soul.
            // value = 0, hue and saturation are undefined
            $h = $s = $v = 0;
            break;

        case $min: // grey
            // saturation = 0, hue is undefined
            $h = $s = 0;
            $v = $max;
            break;

        default: // normal color... color
            $delta = $max - $min;

            // hue...
            if( $r == $max ) {
                // between yellow & magenta
                $h = 0 + ( $g - $b ) / $delta;
            } else if( $g == $max ) {
                // between cyan & yellow
                $h = 2 + ( $b - $r ) / $delta;
            } else {
                // between magenta & cyan
                $h = 4 + ( $r - $g ) / $delta;
            }
            // ...convert hue to degrees
            $h *= 60;
            if($h < 0 ) {
                $h += 360;
            }
            // saturation
            $s = $delta / $max;
            // value
            $v = $max;
        }

        return new self($h, $s, $v);
    }

    /**
     * Pull the HSV components out of the array and return a color model object.
     * Hue is a integer degree between from 0 to 360 and the Saturation and
     * Value are floats between 0 and 1.
     * @param   array $array with Hue as first element, Saturation as the
     *          second and Value as the third.
     * @return  Image_Color2_Model_Hsv
     */
    static public function fromArray($array)
    {
        return new self($array[0], $array[1], $array[2]);
    }

    /**
     * Pull the HSV components out of the string and return a color model
     * object. Hue is a integer degree between from 0 to 360 and the Saturation
     * and Value are either percentages or values between 0 and 1.
     * @param   string $str in the form '120, 25%, 50%' or '120, .25, .50'
     * @return  Image_Color2_Model_Hsv
     */
    static public function fromString($str)
    {
        // split it by commas or spaces
        $a = preg_split('/[, ]/', $str, -1, PREG_SPLIT_NO_EMPTY);

        $h = $a[0];
        $s = substr($a[1], -1) == '%' ? ((float) $a[1]) / 100 : (float) $a[1];
        $v = substr($a[2], -1) == '%' ? ((float) $a[2]) / 100 : (float) $a[2];

        return new self($h, $s, $v);
    }

    /**
     * {@inheritdoc}
     * @return  array A PEAR style RGB array containing three integers
     *          from 0 to 255 for the Red, Green, and Blue color components
     *          followed by a 'type' => 'rgb' element.
     */
    public function getRgb()
    {
        if ($this->_s == 0) {
            // saturation is 0 so it's a grey
            $r = $g = $b = $this->_v;
        } else {
            $h = $this->_h / 60.0;
            $s = $this->_s;
            $v = $this->_v;

            $hi = floor($h);
            $f = $h - $hi;
            $p = ($v * (1.0 - $s));
            $q = ($v * (1.0 - ($f * $s)));
            $t = ($v * (1.0 - ((1.0 - $f) * $s)));

            switch( $hi ) {
            case 0:
                $r = $v;
                $g = $t;
                $b = $p;
                break;

            case 1:
                $r = $q;
                $g = $v;
                $b = $p;
                break;

            case 2:
                $r = $p;
                $g = $v;
                $b = $t;
                break;

            case 3:
                $r = $p;
                $g = $q;
                $b = $v;
                break;

            case 4:
                $r = $t;
                $g = $p;
                $b = $v;
                break;

            default:
                $r = $v;
                $g = $p;
                $b = $q;
                break;
            }
        }
        // add .5 and cast to an int to round the values. calling round()
        // returns a float so you'd have to cast it back anyway. this avoids
        // the overhead of a function call.
        return array(
            (integer) ($r * 255 + 0.5),
            (integer) ($g * 255 + 0.5),
            (integer) ($b * 255 + 0.5),
            'type' => 'rgb'
        );
    }

    /**
     * {@inheritdoc}
     * @return  array
     */
    public function getArray()
    {
        return array(
            $this->_h,
            $this->_s,
            $this->_v,
            'type' => 'hsv'
        );
    }

    /**
     * {@inheritdoc}
     * @return  string In the format 'H S% V%'.
     */
    public function getString()
    {
        return $this->_h . ', '
            . round($this->_s * 100) . '%, '
            . round($this->_v * 100) . '%';
    }
}

?>
