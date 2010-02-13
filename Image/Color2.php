<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Color2.php is the root of the Image_Colro2 package.
 *
 * PHP version 5
 *
 * @category  Image
 * @package   Image_Color2
 * @author    andrew morton <drewish@katherinehouse.com>
 * @copyright 2005 Andrew Morton
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @version   SVN: $Id$
 * @link      http://pear.php.net/package/Image_Color2
 */

/**
 * As a PEAR package, all exceptions are either PEAR_Exception or derived from
 * it.
 */
require_once 'PEAR/Exception.php';
/**
 * This class requires the Hex color model because it's the standard/simplest
 * way to represent an RGB values as a string.
 */
require_once 'Image/Color2/Model/Hex.php';
/**
 * The class requires the Named color model because it's cool to be able to
 * do things like $color = Image_Color2('teal') and have it work.
 */
require_once 'Image/Color2/Model/Named.php';

/**
 * Image_Color2 is a PHP5 package to convert between RGB and various other
 * color models.
 *
 * Here it is in action:
 * <code>
 * <?php
 * require_once 'Image/Color2.php';
 *
 * \/\/ load a named string and view it as a hex string
 * $red = new Image_Color2('red');
 * print $red->getHex() . "\n";     # '#ff0000'
 *
 *  \/\/ load a hex string and view it as an RGB array
 * $blue = new Image_Color2('#0000ff');
 * var_dump($blue->getRgb());       # array(0, 0, 255, 'type' => 'rgb')
 *
 * \/\/ find the average of red and blue (i.e. mix them)...
 * $avg = Image_Color2::average($red, $blue);
 * \/\/ ...then convert it to named color
 * print $avg->convertTo('named')->getString() . "\n";  # 'purple'
 *
 * \/\/ convert blue from RGB to HSV...
 * $hsv = $blue->convertTo('hsv');
 * \/\/ ...then display it as an HSV string and array
 * print $hsv->getString() . "\n";  # '240 100% 100%'
 * print_r($hsv->getArray());       # array(240, 100, 100, 'type' => 'hsv')
 * ?>
 * </code>
 *
 * When converting from one color model to another, say HSL to HSV, an
 * intermediate RGB value is used. Each color model has a distinct gamut, or
 * range of expressible color values, and the use of an intermediate value
 * makes the conversions very imprecise. As a result, the output of these
 * conversions should be viewed as an approximation, unfit for any application
 * where color matching is important.
 *
 * A large portion of the code for this package was derived from the work of
 * Jason Lotito and the other contributors to Image_Color.
 *
 * @category  Image
 * @package   Image_Color2
 * @author    andrew morton <drewish@katherinehouse.com>
 * @copyright 2005 Andrew Mortin
 * @license   http://opensource.org/licenses/lgpl-license.php
 *            GNU Lesser General Public License, Version 2.1
 * @version   Release: 0.1.2
 * @link      http://pear.php.net/package/Image_Color2
 * @see       Image_Color
 * @todo      Figure out a clean way to support alpha channels. This class
 *            will preserve them but as soon as you call a color model for
 *            conversion they'll be discarded. I think this class will need
 *            to maintain a separate variable for the alpha channel it really
 *            seems independent of the color model. 50% red would be the same
 *            no mater how you represent it.
 */
class Image_Color2
{
    /**
     * RGB value of the color. After it's assigned by the constructor it should
     * never be null.
     * @var array
     */
    protected $rgb;
    /**
     * Color model used to read a non-RGB color. This is assigned by the
     * constructor. If the source color is RGB no color model is needed so this
     * will be null.
     * @var Image_Color2_Model
     */
    protected $model = null;

    /**
     * Construct a color from a string, array, or an instance of
     * Image_Color2_Model.
     * <code>
     * \/\/ from a named string
     * $red = new Image_Color2('red');
     * print $red->getHex();    \/\/ '#ff0000'
     *
     * \/\/ from a hex string
     * $blue = new Image_Color2('#0000ff');
     * print $blue->getHex();   \/\/ '#0000ff'
     *
     * \/\/ from an array
     * $black = new Image_Color2(array(0,0,0));
     * print $black->getHex();  \/\/ '#000000'
     * </code>
     *
     * @param array|string|Image_Color2_Model $src specifying a color.
     *          Non-RGB arrays should include the type element to specify a
     *          color model. Strings will be interpreted as hex if they
     *          begin with a #, otherwise they'll be treated as named colors.
     *
     * @throws  PEAR_Exception if the color cannot be loaded.
     * @uses    createModelReflectionMethod() If the color is non-RGB the
     *          function is used to construct an Image_Color2_Model for
     *          conversion.
     */
    public function __construct($src)
    {
        if (is_array($src)) {
            // check if a type parameter was offered up.
            $type = (isset($src['type'])) ? $src['type'] : '';
            // type needs to be a proper case to match the class name.
            $type = ucwords($type);

            if (!$type || $type == 'Rgb') {
                $src['type'] = 'rgb';
                $this->model = null;
                $this->rgb = $src;
            } else {
                $method = self::createModelReflectionMethod($type, 'fromArray');
                $this->model = $method->invoke(null, $src);
            }
        } else if (is_string($src)) {
            if ('#' == substr($src, 0, 1)) {
                $this->model = Image_Color2_Model_Hex::fromString($src);
            } else {
                $this->model = Image_Color2_Model_Named::fromString($src);
            }
        } else if ($src instanceof Image_Color2_Model) {
            $this->model = $src;
        }

        // at this point we either have a model, an rgb value, or a problem.
        if (!is_null($this->model)) {
            $this->rgb = $this->model->getRgb();
        }
        if (is_null($this->rgb)) {
            throw new PEAR_Exception('Invalid color definition.');
        }
    }

    /**
     * Return a ReflectionMethod of a Image_Color2_Model implementation found in
     * the Image/Color2/Model directory.
     *
     * @param string $type       Name of a ColorModel implementation (i.e. for
     *                           Image_Color2_Model_Hsv this would be 'hsv').
     * @param string $methodName Name of a static factory method on the
     *          ColorModel interface ('fromArray', 'fromString', or 'fromRgb').
     *
     * @return  ReflectionMethod
     * @throws  PEAR_Exception if the class cannot be loaded, or it does not
     *          implement the Image_Color2_Model interface.
     * @uses    Image_Color2_Model As the interface for color conversion.
     * @internal
     */
    protected static function createModelReflectionMethod($type, $methodName)
    {
        $type = ucfirst($type);
        $classpath = 'Image/Color2/Model/' . $type . '.php';
        if (!include_once $classpath) {
            throw new PEAR_Exception(
                "File '{$classpath}' for $type was not found.");
        }
        $classname = 'Image_Color2_Model_' . $type;
        if (!class_exists($classname)) {
            throw new PEAR_Exception(
                "Class '{$classname}' for $type was not found.");
        }
        $reflect = new ReflectionClass($classname);
        if (!$reflect->implementsInterface('Image_Color2_Model')) {
            throw new PEAR_Exception(
                "Class '{$classname}' doesn't implement Image_Color2_Model.");
        }
        return $reflect->getMethod($methodName);
    }

    /**
     * Return the average of the RGB value of two Image_Color2 objects. If
     * both objects have an alpha channel it will be averaged too.
     * <code>
     * $red = new Image_Color2('red');
     * $blue = new Image_Color2('blue');
     * $color = Image_Color2::average($red, $blue);
     * print $color->convertTo('named')->getString(); \/\/ 'purple'
     * </code>
     *
     * @param Image_Color2 $left  Left color
     * @param Image_Color2 $right Right color
     *
     * @return Image_Color2
     */
    public static function average(Image_Color2 $left, Image_Color2 $right)
    {
        $lrgb = $left->getRgb();
        $rrgb = $right->getRgb();

        // remove the type element so we can properly compare lengths
        unset($lrgb['type']);
        unset($rrgb['type']);

        // the color may be RGB or RGBA, either way, they need to be the same
        // length.
        $size = min(count($lrgb), count($rrgb));

        // find the average of each pair of elements
        $avg = array();
        for ($i = 0; $i < $size; $i++) {
            $avg[] = (integer) ((($lrgb[$i] + $rrgb[$i] ) / 2) + 0.5);
        }
        return new Image_Color2($avg);
    }

    /**
     * Return a copy of this color converted to another color model.
     * <code>
     * $blue = new Image_Color2('#0000ff');
     * $hsv = $blue->convertTo('hsv');
     * print $hsv->getString(); \/\/ '240 100% 100%'
     * </code>
     *
     * @param string $type Name of a color model. If this variable is foo then a
     *                     class named Image_Color2_Model_Foo is required.
     *
     * @return  Image_Color2
     * @throws  PEAR_Exception if the desired color model cannot be found or it
     *          cannot convert the color.
     * @uses    createModelReflectionMethod() The function is used to
     *          construct an Image_Color2_Model that is passed back to the
     *          constructor.
     */
    public function convertTo($type)
    {
        $method = self::createModelReflectionMethod($type, 'fromRgb');
        $model = $method->invoke(null, $this->rgb);
        if (is_null($model)) {
            throw new PEAR_Exception(
                "The '{$type}' color model couldn't convert the color.");
        } else {
            return new Image_Color2($model);
        }
    }

    /**
     * Return the color as a PEAR style RGB array.
     *
     * The optional 'type' => 'rgb' element should always be included.
     *
     * <code>
     * $color = new Image_Color2(array(0,128,255));
     * print_r($color->getRgb()); \/\/ array(0, 128, 255, 'type' => 'rgb')
     *
     * \/\/ While PHP barfs if you write:
     * print $color->getRgb()[0];     \/\/ NOT VALID
     * \/\/ You can pass in an optional index parameter for the same effect:
     * print $color->getRgb(2);       \/\/ 255
     * print $color->getRgb('type');  \/\/ 'rgb'
     * </code>
     *
     * @param mixed $index An optional index value to select an element of
     *                     the array. A null value returns the entire array.
     *
     * @return  mixed An array if no index parameter is provided. Otherwise,
     *          provided the index is valid, a member of the array.
     * @uses    $rgb Returns a copy of the array.
     */
    public function getRgb($index = null)
    {
        // return an element if requested
        if (isset($index)) {
            return $this->rgb[$index];
        } else {
            return $this->rgb;
        }
    }

    /**
     * Return the color in a color model dependant, array format. If the color
     * was specified as an RGB array this will return the same results as
     * getRgb(). Otherwise, the results depend on the underlying color model.
     *
     * <code>
     * $color = new Image_Color2(array(0,128,255));
     * print_r($color->getArray());     \/\/ array(0, 128, 255, 'type' => 'rgb')
     *
     * \/\/ While PHP barfs if you write:
     * print $color->getArray()[0];     \/\/ NOT VALID
     * \/\/ You can pass in an optional index parameter for the same effect:
     * print $color->getArray(2);       \/\/ 255
     * print $color->getArray('type');  \/\/ 'rgb'
     * </code>
     *
     * @param mixed $index An optional index value to select an element of
     *                     the array. A null value returns the entire array.
     *
     * @return  mixed An array if no index is provided. Otherwise, provided the
     *          index is valid, a member of the array.
     * @uses    $rgb If the color was specified as RGB.
     * @uses    $model If the color wasn't specified as RGB.
     * @uses    Image_Color2_Model::getArray() For the color conversion if it
     *          wasn't originally RGB.
     */
    public function getArray($index = null)
    {
        // get the array
        if (is_null($this->model)) {
            $ret = $this->rgb;
        } else {
            $ret = $this->model->getArray();
        }

        // return an element if requested
        if (isset($index)) {
            return $ret[$index];
        } else {
            return $ret;
        }
    }

    /**
     * Return a hex string representation of the color.
     *
     * <code>
     * $color = new Image_Color2(array(171, 205, 239));
     * print $color->getHex(); \/\/ '#abcdef'
     * </code>
     *
     * To obtain a websafe color, convert it using the
     * {@link Image_Color2_Model_WebsafeHex websafe hex color model}:
     * <code>
     * $color = new Image_Color2('#abcdef');
     * print $color->convertTo('WebsafeHex')->getString(); \/\/ '#99ccff'
     * </code>
     *
     * @return  string  Hex RGB string in the form #abcdef.
     * @uses    Image_Color2_Model_Hex::getString()
     */
    public function getHex()
    {
        return Image_Color2_Model_Hex::fromRgb($this->rgb)->getString();
    }

    /**
     * Return the color as a string. If the color was specified as an RGB array
     * this is exactly the same as calling getHex(). Otherwise, the results
     * depend on the underlying color model.
     *
     * <code>
     * $red = new Image_Color2(array(255,0,0));
     * print $red->getString();     \/\/ '#ff0000'
     *
     * $orange = new Image_Color2('orange');
     * print $orange->getString();  \/\/ 'orange'
     *
     * $hsl = $orange->convertTo('hsl');
     * print $hsl->getString();     \/\/ '38 100% 50%'
     * </code>
     *
     * @return  string
     * @uses    Image_Color2_Model::getString() If the color wasn't originally
     *          RGB.
     * @uses    getHex() If the color was originally specified as RGB.
     */
    public function getString()
    {
        if (is_null($this->model)) {
            return $this->getHex();
        } else {
            return $this->model->getString();
        }
    }
}

?>