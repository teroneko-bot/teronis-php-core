<?php namespace Teronis\Core;

function toBytesString($bytes, $unit = "", $precision = 0)
{
    return transformBytes($bytes, $unit, $precision) . $unit;
}

function transformBytes($bytes, &$unit = "", $precision = 0)
{
    $unit = strtolower($unit);
    $value = 0;

    $units = array("b" => 0, "kb" => 1, "mb" => 2, "gb" => 3, "tb" => 4,
        "pb" => 5, "eb" => 6, "zb" => 7, "yb" => 8);

    if ($bytes > 0) {
        // If no unit is passed, then get it by the exponent of the bytes.
        if (!array_key_exists($unit, $units)) {
            $pow = floor(log($bytes) / log(1024));
            $unit = array_search($pow, $units);
        }

        // Calculate byte value by prefix
        $value = ($bytes / pow(1024, $units[$unit]));
    }

    // If decimals is not numeric or decimals is less than 0, then set default value.
    if (!is_numeric($precision) || $precision < 0) {
        $precision = 2;
    }

    return (float) sprintf('%.' . $precision . 'f ', $value);
}
