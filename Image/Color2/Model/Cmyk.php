<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Cmyk.php contains code for converting between the RGB and CMYK color models.
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
 * CMYK is a subtractive colormodel used in printing.
 *
 * Colors are represented using Cyan, Magenta, Yellow, and blacK components
 * (the K actually stands for key plate which is typically printed in black).
 *
 * The conversions done by this class are very crude. The results should only be
 * considered as a rough approximation.
 *
 * @category    Image
 * @package     Image_Color2
 * @subpackage  Model
 * @author      andrew morton <drewish@katherinehouse.com>
 * @copyright   2005
 * @license     http://opensource.org/licenses/lgpl-license.php
 *              GNU Lesser General Public License, Version 2.1
 * @link        http://pear.php.net/package/Image_Color2
 * @link        http://en.wikipedia.org/wiki/CMYK_color_space
 */
class Image_Color2_Model_Cmyk implements Image_Color2_Model {
    /**
     * Cyan component
     * @var float 0-1
     */
    protected $_c;
    /**
     * Magneta component
     * @var float 0-1
     */
    protected $_m;
    /**
     * Yellow component
     * @var float 0-1
     */
    protected $_y;
    /**
     * Black component
     * @var float 0-1
     */
    protected $_k;

    /**
     * Constuct from four floats.
     * @param   float $c 0.0 - 1.0 Cyan
     * @param   float $m 0.0 - 1.0 Magenta
     * @param   float $y 0.0 - 1.0 Yellow
     * @param   float $k 0.0 - 1.0 Black
     */
    protected function __construct($c, $m, $y, $k)
    {
        // special case: black
        if ($k >= 1.0) {
            $this->_c = $this->_m = $this->_y = 0.0;
            $this->_k = 1.0;
        } else {
            $this->_c = (float) $c;
            $this->_m = (float) $m;
            $this->_y = (float) $y;
            $this->_k = (float) $k;
        }
    }

    /**
     * @param   array   $rgb A PEAR style RGB array containing three integers
     *          from 0 to 255 for the Red, Green, and Blue color components
     *          followed by a 'type' => 'rgb' element.
     * @return  Image_Color2_Model_Hsl
     */
    static public function fromRgb($rgb)
    {
        // compute the tCMY temporary
        $tC = 1 - ($rgb[0] / 255);
        $tM = 1 - ($rgb[1] / 255);
        $tY = 1 - ($rgb[2] / 255);

        $min = min($tC, $tM, $tY) ;
        if ($min == 1) {
            return new self(0,0,0,1);
        } else {
            // compute the tK temporary
            $K = $min;
            $tK = 1 - $K;
            return new self(
                ($tC - $K) / $tK,
                ($tM - $K) / $tK,
                ($tY - $K) / $tK,
                $K
            );
        }
    }

    /**
     * @param   array $array An array with four integers between 0 and 100 for
     *          the each of the Cyan, Magenta, Yellow and blacK color
     *          components.
     * @return  Image_Color2_Model_Hsl
     */
    static public function fromArray($array)
    {
        return new self($array[0], $array[1], $array[2], $array[3]);
    }

    /**
     * {@inheritdoc}
     * @param   string $str In the format 'C%, M%, Y%, K%'. The percent sign,
     *          comma, or space may be used to separate the channels.
     * @return  Image_Color2_Model_Hsl
     */
    static public function fromString($str)
    {
        // split it by commas or spaces
        $array = preg_split('/[, ]/', $str, -1, PREG_SPLIT_NO_EMPTY);

        // for each channel, if it has a % sign divide by 100.
        for ($i = 0; $i < 4; $i++) {
            if (substr($array[$i], -1) == '%') {
                $array[$i] = ((float) $array[$i]) / 100;
            } else {
                $array[$i] = ((float) $array[$i]);
            }
        }

        return new self($array[0], $array[1], $array[2], $array[3]);
    }

    /**
     * {@inheritdoc}
     * @return  array A PEAR style RGB array containing three integers
     *          from 0 to 255 for the Red, Green, and Blue color components
     *          followed by a 'type' => 'rgb' element.
     */
    public function getRgb()
    {
        $r = 1 - ($this->_c * (1 - $this->_k) + $this->_k);
        $g = 1 - ($this->_m * (1 - $this->_k) + $this->_k);
        $b = 1 - ($this->_y * (1 - $this->_k) + $this->_k);

        // round to an integer by adding half and then casting.
        return array(
            (integer) (($r * 255) + 0.5),
            (integer) (($g * 255) + 0.5),
            (integer) (($b * 255) + 0.5),
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
            $this->_c, $this->_m, $this->_y, $this->_k, 'type' => 'cmyk'
        );
    }

    /**
     * {@inheritdoc}
     * @return  string In the format 'C%, M%, Y%, K%'.
     */
    public function getString()
    {
        return round($this->_c * 100) . '%, '
             . round($this->_m * 100) . '%, '
             . round($this->_y * 100) . '%, '
             . round($this->_k * 100) . '%';
    }
}

?>
