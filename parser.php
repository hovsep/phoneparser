<?php

function parse($raw = null)
{
    $numbers = [];

    $raw = str_replace('+7', '8', $raw);//Replace +7 with 8
    $raw = preg_replace("~[a-z]~msi", '', $raw);//Remove letters

    $m = [];
    preg_match_all('~((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}~msi', $raw, $m);//General phone number

    if (!empty($m[0])) {
        $numbers = array_unique(
            array_filter($m[0], function ($item) {
                return !empty($item);
            })
        );
        array_walk($numbers, function(&$number) {
            if (strlen($number) == 10) {
                $number = '8' . $number;
            }

            $number = preg_replace("~[^0-9]~msi", '', $number);//Remove any non-digit letter
        });
    }

    return $numbers;
}