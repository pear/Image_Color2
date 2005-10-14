<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Hsl.php contains code for converting between the RGB and HSL color models.
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
 * HSL (also called HSI or HLS) color model defines colors in terms of Hue,
 * Saturation, and Lightness (also Luminance, Luminosity or Intensity).
 *
 * CSS3 allows the use of this color model to define colors.
 *
 * @todo Need to do more testing on the conversions done by this class.
 *
 * @category    Image
 * @package     Image_Color2
 * @subpackage  Model
 * @author      andrew morton <drewish@katherinehouse.com>
 * @copyright   2005
 * @license     http://opensource.org/licenses/lgpl-license.php
 *              GNU Lesser General Public License, Version 2.1
 * @link        http://pear.php.net/package/Image_Color2
 * @link        http://en.wikipedia.org/wiki/HLS_color_space
 */
class Image_Color2_Model_Hsl implements Image_Color2_Model {
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
     * Lightness component
     * @var float 0.0-1.0
    */
    protected $_l;

    /**
     * Constuct from a integer for Hue, and two floats for Saturation and
     * Lightness.
     * @param   integer $h 0 - 360
     * @param   float   $s 0.0 - 1.0
     * @param   float   $l 0.0 - 1.0
     */
    protected function __construct($h, $s, $l)
    {
        $this->_h = (integer) $h;
        $this->_s = $s;
        $this->_l = $l;
    }

    /**
     * @param   array   $rgb A PEAR style RGB array containing three integers
     *          from 0 to 255 for the Red, Green, and Blue color components
     *          followed by a 'type' => 'rgb' element.
     * @return  Image_Color2_Model_Hsl
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
            $h = $s = $l = 0;
            break;

        case $min: // grey
            // saturation = 0, hue is undefined
            $h = $s = 0;
            $l = $max;
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
            $s = $delta;
            // lightness
            $l = ($max + $min) / 2;
        }

        return new self($h, $s, $l);
    }

    /**
     * Pull the HSL components out of the array and return a color model
     * object. Hue is a integer degree between from 0 to 360 and the Saturation
     * and Lightness are floats between 0 and 1.
     * @param   array $array An array with the Hue as an integer 0-360 in the
     *          first element, Saturation and Lightness as floats 0.0-1.0 in the
     *          second as the third elements respectively.
     * @return  Image_Color2_Model_Hsl
     */
    static public function fromArray($array)
    {
        return new self($array[0], $array[1], $array[2]);
    }

    /**
     * Pull the HSL components out of the string and return a color model
     * object. Hue is a integer degree between 0 and 360 while the Saturation
     * and Lightness are either percentages or values between 0 and 1.
     * @param   string $str In the form '120, 25%, 50%' or '120, .25, .50'
     * @return  Image_Color2_Model_Hsl
     */
    static public function fromString($str)
    {
        // split it by commas or spaces
        $a = preg_split('/[, ]/', $str, -1, PREG_SPLIT_NO_EMPTY);

        $h = $a[0];
        $s = substr($a[1], -1) == '%' ? ((float) $a[1]) / 100 : (float) $a[1];
        $l = substr($a[2], -1) == '%' ? ((float) $a[2]) / 100 : (float) $a[2];

        return new self($h, $s, $l);
    }

    /**
     * {@inheritdoc}
     * @return  array A PEAR style RGB array containing three integers
     *          from 0 to 255 for the Red, Green, and Blue color components
     *          followed by a 'type' => 'rgb' element.
     */
    public function getRgb()
    {
        // copy the members to locals for ease of reading
        $h = $this->_h / 360;
        $s = $this->_s;
        $l = $this->_l;
        if ($s == 0.0) {
            // saturation is 0 so it's a grey
            $r = $g = $b = $l;
        } else {
            if ($l < 0.5) {
                $temp2 = $l * (1.0 + $s);
            } else {
                $temp2 = ($l + $s) - ($l * $s);
            }
            $temp1 = (2.0 * $l) - $temp2;
            $r = self::rgbFromHue($temp1, $temp2, $h + (1 / 3));
            $g = self::rgbFromHue($temp1, $temp2, $h);
            $b = self::rgbFromHue($temp1, $temp2, $h - (1 / 3));
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
     * This is a private function to convert the hue information into an RGB
     * component.
     * @param   float
     * @param   float
     * @param   float
     * @return  float
     */
    private static function rgbFromHue( $v1, $v2, $vH )
    {
        if ( $vH < 0 ) {
            $vH += 1;
        }
        if ( $vH > 1 ) {
            $vH -= 1;
        }

        if ( 6 * $vH < 1 ) {
            return $v1 + ( $v2 - $v1 ) * 6 * $vH;
        } else if ( 2 * $vH < 1 ) {
            return $v2;
        } else if ( 3 * $vH < 2 ) {
            return $v1 + ( $v2 - $v1 ) * ( ( 2 / 3 ) - $vH ) * 6;
        } else {
            return $v1;
        }
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
            $this->_l,
            'type' => 'hsl'
        );
    }

    /**
     * {@inheritdoc}
     * @return  string In the format 'H S% L%'.
     */
    public function getString()
    {
        return sprintf(
            '%d, %d%%, %d%%',
            $this->_h,
            round($this->_s * 100),
            round($this->_l * 100)
        );
    }
}

?>
