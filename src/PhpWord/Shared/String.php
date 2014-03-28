<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Shared;

/**
 * Common string functions
 */
class String
{
    /**
     * Control characters array
     *
     * @var string[]
     */
    private static $_controlCharacters = array();

    /**
     * Build control characters array
     */
    private static function _buildControlCharacters()
    {
        for ($i = 0; $i <= 19; ++$i) {
            if ($i != 9 && $i != 10 && $i != 13) {
                $find = '_x' . sprintf('%04s', strtoupper(dechex($i))) . '_';
                $replace = chr($i);
                self::$_controlCharacters[$find] = $replace;
            }
        }
    }

    /**
     * Convert from OpenXML escaped control character to PHP control character
     *
     * Excel 2007 team:
     * ----------------
     * That's correct, control characters are stored directly in the shared-strings table.
     * We do encode characters that cannot be represented in XML using the following escape sequence:
     * _xHHHH_ where H represents a hexadecimal character in the character's value...
     * So you could end up with something like _x0008_ in a string (either in a cell value (<v>)
     * element or in the shared string <t> element.
     *
     * @param    string $value Value to unescape
     * @return    string
     */
    public static function controlCharacterOOXML2PHP($value = '')
    {
        if (empty(self::$_controlCharacters)) {
            self::_buildControlCharacters();
        }

        return str_replace(array_keys(self::$_controlCharacters), array_values(self::$_controlCharacters), $value);
    }

    /**
     * Convert from PHP control character to OpenXML escaped control character
     *
     * Excel 2007 team:
     * ----------------
     * That's correct, control characters are stored directly in the shared-strings table.
     * We do encode characters that cannot be represented in XML using the following escape sequence:
     * _xHHHH_ where H represents a hexadecimal character in the character's value...
     * So you could end up with something like _x0008_ in a string (either in a cell value (<v>)
     * element or in the shared string <t> element.
     *
     * @param    string $value Value to escape
     * @return    string
     */
    public static function controlCharacterPHP2OOXML($value = '')
    {
        if (empty(self::$_controlCharacters)) {
            self::_buildControlCharacters();
        }

        return str_replace(array_values(self::$_controlCharacters), array_keys(self::$_controlCharacters), $value);
    }

    /**
     * Check if a string contains UTF-8 data
     *
     * @param string $value
     * @return boolean
     */
    public static function isUTF8($value = '')
    {
        return $value === '' || preg_match('/^./su', $value) === 1;
    }
}