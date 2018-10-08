<?php namespace Teronis\Core;

const BYTE_UNITS = array("b" => 0, "kb" => 1, "mb" => 2, "gb" => 3, "tb" => 4,
    "pb" => 5, "eb" => 6, "zb" => 7, "yb" => 8);

function formatBytes(float $bytes, $byteUnit = "", $precision = 0): string
{
    return changeByteUnit($bytes, $byteUnit, $precision) . $byteUnit;
}

function parseByteVariant(string $bytesVariant, $byteUnit = ""): float
{
    $_bytesVariant = floatval($bytesVariant);

    if (!$byteUnit) {
        $result = preg_match("/[a-zA-Z]+(?![0-9])$/", $bytesVariant, $matches);

        $doesByteUnitExist = function (&$_byteUnit) use ($matches) {
            $_byteUnit = strtolower($matches[0]);
            return array_key_exists($_byteUnit, BYTE_UNITS);
        };

        if ($result && $doesByteUnitExist($_byteUnit)) {
            $byteUnit = $_byteUnit;
        } else {
            $byteUnit = _getByteUnit($_bytesVariant);
        }
    }

    return $_bytesVariant * pow(1024, BYTE_UNITS[$byteUnit]);
}

/**
 * Changes bytes to another format like KB or MB.
 *
 * @param float $bytes
 * @param string $byteUnit
 * @param integer $precision
 * @return void
 */
function changeByteUnit(float $bytes, $byteUnit = "", $precision = 0): float
{
    $this->_changeByteUnit($bytes, $byteUnit, $precision);
}

function _changeByteUnit(float $bytes, &$byteUnit = "", $precision = 0): float
{
    $byteUnit = strtolower($byteUnit);
    $value = 0;

    if ($bytes > 0) {
        // If no unit is passed, then get it by the exponent of the bytes.
        if (!array_key_exists($byteUnit, BYTE_UNITS)) {
            $byteUnit = _getByteUnit($bytes);
        }

        // Calculate byte value by prefix
        $value = ($bytes / pow(1024, BYTE_UNITS[$byteUnit]));
    }

    // If decimals is not numeric or decimals is less than 0, then set default value.
    if (!is_numeric($precision) || $precision < 0) {
        $precision = 2;
    }

    return (float) sprintf('%.' . $precision . 'f ', $value);
}

function _getByteUnit(float $bytesVariant): string
{
    $pow = floor(log($bytesVariant) / log(1024));
    $byteUnit = array_search($pow, BYTE_UNITS);
    return $byteUnit;
}

/**
 * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
 * keys to arrays rather than overwriting the value in the first array with the duplicate
 * value in the second array, as array_merge does. I.e., with array_merge_recursive,
 * this happens (documented behavior):
 *
 * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
 *     => array('key' => array('org value', 'new value'));
 *
 * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
 * Matching keys' values in the second array overwrite those in the first array, as is the
 * case with array_merge, i.e.:
 *
 * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
 *     => array('key' => array('new value'));
 *
 * Parameters are passed by reference, though only for performance reasons. They're not
 * altered by this function.
 *
 * @param array $array1
 * @param array $array2
 * @return array
 * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
 * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
 * @author C. Loncar <giteroneko (at) gmail (dot) com>
 */
function array_merge_recursive_distinct(array &$array1, array &$array2, bool $applyChangesOnArray1 = false): array
{
    if ($applyChangesOnArray1) {
        $merged = &$array1;
    } else {
        $merged = $array1;
    }

    foreach ($array2 as $key => &$value) {
        if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
            $merged[$key] = array_merge_recursive_distinct($merged[$key], $value);
        } else {
            $merged[$key] = $value;
        }
    }

    return $merged;
}