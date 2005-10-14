<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Model.php is a PHP5 package to convert between RGB and various other
 * color models.
 *
 * PHP version 5
 *
 * @category    Image
 * @package     Image_Color2
 * @author      andrew morton <drewish@katherinehouse.com>
 * @copyright   2005
 * @license     http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @version     $Id$
 * @link        http://pear.php.net/package/Image_Color2
 */


/**
 * An interface for color model classes to allow conversion of colors to and
 * from RGB. This class should be imutable, meaning that once it's constructed
 * the value is not changed.
 *
 * @category    Image
 * @package     Image_Color2
 * @author      andrew morton <drewish@katherinehouse.com>
 * @copyright   2005
 * @license     http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 */
interface Image_Color2_Model {
    /**
     * Create an Image_Color2_Model from an RGB array.
     *
     * {@internal
     * If there's a problem the implementation may either return null or throw
     * an exception. The exception is recommended as you are able to provide
     * more detailed information on the failure.
     *
     * Be aware that the array may contain an alpha channel. This can be safely
     * ignored as the alpha channel is a responsibility of the Image_Color2
     * class.}}
     *
     * @param   array $rgb A PEAR style RGB array containing three integers
     *          from 0 to 255 for the Red, Green, and Blue color components
     *          followed by a 'type' => 'rgb' element.
     * @return  Image_ColorModel
     * @throws  PEAR_Exception On an error an exception maybe thrown or null
     *          returned, it's up to the implementation.
     * @see     getRgb()
     */
    static public function fromRgb($rgb);

    /**
     * Create an Image_Color2_Model from an array of values.
     *
     * {@internal
     * If there's a problem the implementation may either return null or throw
     * an exception. The exception is recommended as you are able to provide
     * more detailed information on the failure.
     *
     * If a channel element in the array is a string with a trailing percent
     * sign (%) it should be divided by 100, then treated as a float and forced
     * into a 0.0 to 1.0 range.}}
     *
     * Color models like CMYK and HSL have some components that are typically
     * expressed as either percentages or floating point values from 0.0 to 1.0.
     * This function tries to be very accomidating, if the value is followed by
     * a percent sign (%) it will be divided by 100 and then treated as a
     * zero to one floating point value.
     *
     * @param   array $array The values and order are specific to each color
     *          model. There may be an optional type element at the end of the
     *          array. i.e: array(0, 255, 0, 'type' => 'rgb')
     * @return  Image_ColorModel
     * @throws  PEAR_Exception On an error an exception maybe thrown or null
     *          returned, it's up to the implementation.
     * @see     getArray()
     */
    static public function fromArray($array);

    /**
     * Create an Image_ColorModel from a color model specific string.
     *
     * {@internal
     * If there's a problem the implementation may either return null or throw
     * an exception. The exception is recommended as you are able to provide
     * more detailed information on the failure.
     *
     * When a channel's value is followed by a % (with no whitespace
     * separating the two) it should be divided by 100, then treated as a
     * floating point value and forced into the 0.0 to 1.0 range.
     *
     * If applicable, color models should allow the channel
     * components to be separated by spaces and commas (i.e. '50% 23% 0%'
     * should be equivalent to '.5, .23, 0' and '50%, 23%, 0%').}}
     *
     * Color models like CMYK and HSL have some components that are typically
     * expressed as either percentages or floating point values from 0.0 to 1.0.
     * This function tries to be very accomidating, if the value is followed by
     * a percent sign (%) it will be divided by 100 and then treated as a
     * zero to one floating point value.
     *
     * The color's channels can be separated using a comma, a space or both.
     *
     * Examples of string formats used by several implementations of this
     * interface:
     * <pre>
     * Hex: '#abcdef'
     * Named: 'black'
     * CMYK: '100% 0% 75% 20%' or '1, 0, .75, .2'
     * HSL: '135, 100%, 40%' or '135 1.0 0.4'
     * </pre>
     *
     * @param   string $str a string in a color model dependant format.
     * @return  Image_ColorModel
     * @throws  PEAR_Exception On an error an exception maybe thrown or null
     *          returned, it's up to the implementation.
     * @see     getString()
     */
    static public function fromString($str);

    /**
     * Get an RGB array of the color.
     *
     * The 'type' => 'rgb' element must be included as the last element.
     *
     * @return  array A PEAR style RGB array containing three integers
     *          from 0 to 255 for the Red, Green, and Blue color components
     *          followed by a 'type' => 'rgb' element.
     * @see     fromRgb()
     */
    public function getRgb();

    /**
     * Get an array of values to represent a color in this color model. This
     * should be parseable by fromArray() and should include a type element.
     *
     * {@internal
     * The 'type' => 'foo' where foo is the color model, element must be included
     * as the last element.}}
     *
     * @return  array An array in a color model dependant format with a
     *          type element.
     * @see     fromArray()
     */
    public function getArray();

    /**
     * Get a string to represent a color in this color model. The string should
     * be parsable by fromString().
     *
     * {@internal
     * Color channels should be separated with a comma and then a space.
     *
     * Integer values (like the Hue in HSL) should be formated as integers.
     * Floating point values that have a range of 0.0 - 1.0 should be converted
     * to a percentage for output.}}
     *
     * <pre>
     * HSL: '135, 100%, 40%'
     * CMYK: '100%, 0%, 75%, 20%'
     * Hex: '#00cc33'
     * </pre>
     *
     * @return  string A string in a color model dependant format.
     * @see     fromString()
     */
    public function getString();
}

?>
