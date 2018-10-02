<?php namespace Teronis\Core;

const BYTE_UNITS = array("b" => 0, "kb" => 1, "mb" => 2, "gb" => 3, "tb" => 4,
    "pb" => 5, "eb" => 6, "zb" => 7, "yb" => 8);

function formatBytes($bytes, $unit = "", $precision = 0)
{
    return changeByteUnit($bytes, $unit, $precision) . $unit;
}

function parseByteVariant(string $bytesVariant, $unit = "")
{
    $_bytesVariant = floatval($bytesVariant);

    if (!$unit) {
        $lastTwoCharacters = strtolower(substr($bytesVariant, -2));

        if (array_key_exists($lastTwoCharacters, BYTE_UNITS)) {
            $unit = $lastTwoCharacters;
        } else {
            $unit = _getByteUnit($_bytesVariant);
        }
    }

    return $_bytesVariant * pow(1024, BYTE_UNITS[$unit]);
}

/**
 * Changes bytes to another format like KB or MB.
 *
 * @param float $bytes
 * @param string $unit
 * @param integer $precision
 * @return void
 */
function changeByteUnit(float $bytes, $unit = "", $precision = 0)
{
    $this->_changeByteUnit($bytes, $unit, $precision);
}

function _changeByteUnit(float $bytes, &$unit = "", $precision = 0)
{
    $unit = strtolower($unit);
    $value = 0;

    if ($bytes > 0) {
        // If no unit is passed, then get it by the exponent of the bytes.
        if (!array_key_exists($unit, BYTE_UNITS)) {
            $unit = _getByteUnit($bytes);
        }

        // Calculate byte value by prefix
        $value = ($bytes / pow(1024, BYTE_UNITS[$unit]));
    }

    // If decimals is not numeric or decimals is less than 0, then set default value.
    if (!is_numeric($precision) || $precision < 0) {
        $precision = 2;
    }

    return (float) sprintf('%.' . $precision . 'f ', $value);
}

function _getByteUnit(float $bytesVariant)
{
    $pow = floor(log($bytesVariant) / log(1024));
    $unit = array_search($pow, BYTE_UNITS);
    return $unit;
}
