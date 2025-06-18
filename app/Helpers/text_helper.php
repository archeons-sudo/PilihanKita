<?php

if (!function_exists('character_limiter')) {
    function character_limiter($str, $n = 500, $end_char = '…')
    {
        $str = trim($str);
        if (mb_strlen($str) <= $n) {
            return $str;
        }
        return mb_substr($str, 0, $n) . $end_char;
    }
}