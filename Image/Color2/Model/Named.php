<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Named.php contains code for matching RGB colors to their names.
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
 * Named color model represents colors by looking up the "webstandard" name
 * in a list of corresponding RGB values.
 *
 * This class is based on code written by Sebastian Bergmann for Image_Color.
 *
 * @category    Image
 * @package     Image_Color2
 * @subpackage  Model
 * @author      andrew morton <drewish@katherinehouse.com>
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   2005
 * @license     http://opensource.org/licenses/lgpl-license.php
 *              GNU Lesser General Public License, Version 2.1
 * @link        http://pear.php.net/package/Image_Color2
 * @todo        Find out and then explain where this list of names came from.
 *              Are there any that we're missing?
 * @todo        Look into rounding colors to the closest named value.
 */
class Image_Color2_Model_Named implements Image_Color2_Model {
    /**
     * Name of this color. It should exist in self::$_colornames.
     * @var string
     */
    protected $_name = '';

    /**
     * Array of named colors and values.
     * @var     array
     * @ignore  so this doesn't clutter up the PHPDocs.
     */
    protected static $_colornames = array(
      'aliceblue'             => array(240, 248, 255),
      'antiquewhite'          => array(250, 235, 215),
      'aqua'                  => array(  0, 255, 255),
      'aquamarine'            => array(127, 255, 212),
      'azure'                 => array(240, 255, 255),
      'beige'                 => array(245, 245, 220),
      'bisque'                => array(255, 228, 196),
      'black'                 => array(  0,   0,   0),
      'blanchedalmond'        => array(255, 235, 205),
      'blue'                  => array(  0,   0, 255),
      'blueviolet'            => array(138,  43, 226),
      'brown'                 => array(165,  42,  42),
      'burlywood'             => array(222, 184, 135),
      'cadetblue'             => array( 95, 158, 160),
      'chartreuse'            => array(127, 255,   0),
      'chocolate'             => array(210, 105,  30),
      'coral'                 => array(255, 127,  80),
      'cornflowerblue'        => array(100, 149, 237),
      'cornsilk'              => array(255, 248, 220),
      'crimson'               => array(220,  20,  60),
      'cyan'                  => array(  0, 255, 255),
      'darkblue'              => array(  0,   0,  13),
      'darkcyan'              => array(  0, 139, 139),
      'darkgoldenrod'         => array(184, 134,  11),
      'darkgray'              => array(169, 169, 169),
      'darkgreen'             => array(  0, 100,   0),
      'darkkhaki'             => array(189, 183, 107),
      'darkmagenta'           => array(139,   0, 139),
      'darkolivegreen'        => array( 85, 107,  47),
      'darkorange'            => array(255, 140,   0),
      'darkorchid'            => array(153,  50, 204),
      'darkred'               => array(139,   0,   0),
      'darksalmon'            => array(233, 150, 122),
      'darkseagreen'          => array(143, 188, 143),
      'darkslateblue'         => array( 72,  61, 139),
      'darkslategray'         => array( 47,  79,  79),
      'darkturquoise'         => array(  0, 206, 209),
      'darkviolet'            => array(148,   0, 211),
      'deeppink'              => array(255,  20, 147),
      'deepskyblue'           => array(  0, 191, 255),
      'dimgray'               => array(105, 105, 105),
      'dodgerblue'            => array( 30, 144, 255),
      'firebrick'             => array(178,  34,  34),
      'floralwhite'           => array(255, 250, 240),
      'forestgreen'           => array( 34, 139,  34),
      'fuchsia'               => array(255,   0, 255),
      'gainsboro'             => array(220, 220, 220),
      'ghostwhite'            => array(248, 248, 255),
      'gold'                  => array(255, 215,   0),
      'goldenrod'             => array(218, 165,  32),
      'gray'                  => array(128, 128, 128),
      'green'                 => array(  0, 128,   0),
      'greenyellow'           => array(173, 255,  47),
      'honeydew'              => array(240, 255, 240),
      'hotpink'               => array(255, 105, 180),
      'indianred'             => array(205,  92,  92),
      'indigo'                => array(75,    0, 130),
      'ivory'                 => array(255, 255, 240),
      'khaki'                 => array(240, 230, 140),
      'lavender'              => array(230, 230, 250),
      'lavenderblush'         => array(255, 240, 245),
      'lawngreen'             => array(124, 252,   0),
      'lemonchiffon'          => array(255, 250, 205),
      'lightblue'             => array(173, 216, 230),
      'lightcoral'            => array(240, 128, 128),
      'lightcyan'             => array(224, 255, 255),
      'lightgoldenrodyellow'  => array(250, 250, 210),
      'lightgreen'            => array(144, 238, 144),
      'lightgrey'             => array(211, 211, 211),
      'lightpink'             => array(255, 182, 193),
      'lightsalmon'           => array(255, 160, 122),
      'lightseagreen'         => array( 32, 178, 170),
      'lightskyblue'          => array(135, 206, 250),
      'lightslategray'        => array(119, 136, 153),
      'lightsteelblue'        => array(176, 196, 222),
      'lightyellow'           => array(255, 255, 224),
      'lime'                  => array(  0, 255,   0),
      'limegreen'             => array( 50, 205,  50),
      'linen'                 => array(250, 240, 230),
      'magenta'               => array(255,   0, 255),
      'maroon'                => array(128,   0,   0),
      'mediumaquamarine'      => array(102, 205, 170),
      'mediumblue'            => array(  0,   0, 205),
      'mediumorchid'          => array(186,  85, 211),
      'mediumpurple'          => array(147, 112, 219),
      'mediumseagreen'        => array( 60, 179, 113),
      'mediumslateblue'       => array(123, 104, 238),
      'mediumspringgreen'     => array(  0, 250, 154),
      'mediumturquoise'       => array(72, 209, 204),
      'mediumvioletred'       => array(199,  21, 133),
      'midnightblue'          => array( 25,  25, 112),
      'mintcream'             => array(245, 255, 250),
      'mistyrose'             => array(255, 228, 225),
      'moccasin'              => array(255, 228, 181),
      'navajowhite'           => array(255, 222, 173),
      'navy'                  => array(  0,   0, 128),
      'oldlace'               => array(253, 245, 230),
      'olive'                 => array(128, 128,   0),
      'olivedrab'             => array(107, 142,  35),
      'orange'                => array(255, 165,   0),
      'orangered'             => array(255,  69,   0),
      'orchid'                => array(218, 112, 214),
      'palegoldenrod'         => array(238, 232, 170),
      'palegreen'             => array(152, 251, 152),
      'paleturquoise'         => array(175, 238, 238),
      'palevioletred'         => array(219, 112, 147),
      'papayawhip'            => array(255, 239, 213),
      'peachpuff'             => array(255, 218, 185),
      'peru'                  => array(205, 133,  63),
      'pink'                  => array(255, 192, 203),
      'plum'                  => array(221, 160, 221),
      'powderblue'            => array(176, 224, 230),
      'purple'                => array(128,   0, 128),
      'red'                   => array(255,   0,   0),
      'rosybrown'             => array(188, 143, 143),
      'royalblue'             => array( 65, 105, 225),
      'saddlebrown'           => array(139,  69,  19),
      'salmon'                => array(250, 128, 114),
      'sandybrown'            => array(244, 164,  96),
      'seagreen'              => array( 46, 139,  87),
      'seashell'              => array(255, 245, 238),
      'sienna'                => array(160,  82,  45),
      'silver'                => array(192, 192, 192),
      'skyblue'               => array(135, 206, 235),
      'slateblue'             => array(106,  90, 205),
      'slategray'             => array(112, 128, 144),
      'snow'                  => array(255, 250, 250),
      'springgreen'           => array(  0, 255, 127),
      'steelblue'             => array( 70, 130, 180),
      'tan'                   => array(210, 180, 140),
      'teal'                  => array(  0, 128, 128),
      'thistle'               => array(216, 191, 216),
      'tomato'                => array(255,  99,  71),
      'turquoise'             => array( 64, 224, 208),
      'violet'                => array(238, 130, 238),
      'wheat'                 => array(245, 222, 179),
      'white'                 => array(255, 255, 255),
      'whitesmoke'            => array(245, 245, 245),
      'yellow'                => array(255, 255,   0),
      'yellowgreen'           => array(154, 205,  50)
    );


    /**
     * Construct a named color.
     *
     * @param   string $name A color name in self::$_colorname. It will be
     *          converted to lowercase and spaces will be removed before trying
     *          to match it.
     */
    protected function __construct($name)
    {
        $name = str_replace(' ', '', strtolower($name));
        if (array_key_exists($name, self::$_colornames)) {
            $this->_name = $name;
        } else {
            throw new PEAR_Exception("The color '{$name}' is unknown.");
        }
    }

    /**
     * @param   array $rgb A PEAR style RGB array containing three integers
     *          from 0 to 255 for the Red, Green, and Blue color components
     *          followed by a 'type' => 'rgb' element.
     * @return  Image_Color2_Model_Named
     */
    static public function fromRgb($rgb)
    {
        // remove the type so it'll match something in the list.
        unset($rgb['type']);

        $name = array_search($rgb, self::$_colornames, true);
        if ($name === false) {
            throw new PEAR_Exception('There is no name for this color. ');
        }
        return new self($name);
    }

    /**
     * @param   array $array with the color name as a string in the first
     *          element. The name will be converted to lowecase and spaces will
     *          be removed before trying to match it against the list of known
     *          colors.
     * @return  Image_Color2_Model_Named
     */
    static public function fromArray($array)
    {
        return new self($array[0]);
    }

    /**
     *
     * @param   string $str A color name. The name will be converted to
     *          lowecase and spaces will be removed before trying to match it
     *          against the list of known colors.
     * @return  Image_Color2_Model_Named
     */
    static public function fromString($str)
    {
        return new self($str);
    }

    /**
     * {@inheritdoc}
     * @return  array A PEAR style RGB array containing three integers
     *          from 0 to 255 for the Red, Green, and Blue color components
     *          followed by a 'type' => 'rgb' element.
     */
    public function getRgb()
    {
        $rgb = self::$_colornames[$this->_name];
        $rgb['type'] = 'rgb';
        return $rgb;
    }

    /**
     * {@inheritdoc}
     * @return  array An array with two elements, the first is the color name
     *          the second is the type => named.
     */
    public function getArray()
    {
        return array($this->_name, 'type' => 'named');
    }

    /**
     * {@inheritdoc}
     * @return  string The name of the color.
     */
    public function getString()
    {
        return $this->_name;
    }
}

?>
