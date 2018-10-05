<?php namespace Teronis\Core;

class HttpUtilities {
    public static function encodeURIComponent(string $str) :string {
        $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
        return strtr(rawurlencode($str), $revert);
    }

    private function __construct() {}
}