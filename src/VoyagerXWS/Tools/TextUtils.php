<?php

namespace VoyagerXWS\Tools;

class TextUtils
{
    public static function cleanISBN($origString) {
        $result = preg_replace('/\(.+\)(?=$)/','',$origString);

        return trim($result);
    }

    public static function cleanData($origString) {

        // trim pre and post white space
        $result = trim($origString);

        // remove any preceeding white space before punctuation
        $result = preg_replace('/\s+(?=[,:;.?!])/', '', $result);

        // remove trailing slashes
        $result = preg_replace('/\/(?=$)/','', $result);

        // strip trailing '.' (period)
        $result = preg_replace('/\.(?=$)/','', $result);

        return trim($result);
    }

    private static function strStartWith($string, $test) {
        return strpos($string, $test) === 0;
    }

    private static function strEndsWith($string, $test) {
        return strrpos($string, $test) + strlen($test) === strlen($string);
    }
}