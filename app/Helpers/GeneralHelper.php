<?php

if (!function_exists('generateRandomNumber')) {
    function generateRandomNumber($length = 4): int
    {
        $intMin = (10 ** $length) / 10;
        $intMax = (10 ** $length) - 1;

        return mt_rand($intMin, $intMax);
    }
}

if (!function_exists('checkPhoneNumber')) {
    function checkPhoneNumber($cell_number): string
    {
        $cell_number = convertToEnglishDigit($cell_number);
        return str_replace('+98', '0', $cell_number);
    }
}

if (!function_exists('getCompleteIpAddr')) {
    function getCompleteIpAddr(): string
    {
        $ip = '';
        if (!empty($_SERVER['HTTP_AR_REAL_IP'])) {
            $ip .= 'HTTP_AR_REAL_IP => ' . $_SERVER['HTTP_AR_REAL_IP'] . ' | ';
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip .= 'HTTP_X_FORWARDED_FOR => ' . $_SERVER['HTTP_X_FORWARDED_FOR'] . ' | ';
        }

        if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
            $ip .= 'HTTP_X_REAL_IP => ' . $_SERVER['HTTP_X_REAL_IP'] . ' | ';
        }

        if (!empty($_SERVER['SERVER_ADDR'])) {
            $ip = 'SERVER_ADDR => ' . $_SERVER['SERVER_ADDR'] . ' | ';
        }

        if (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip .= 'REMOTE_ADDR => ' . $_SERVER['REMOTE_ADDR'] . ' | ';
        }

        $ip = rtrim($ip, ' | ');
        return $ip;
    }
}

if (!function_exists('generateTransferCode')) {
    function generateTransferCode($length = 15): int
    {
        $intMin = (10 ** $length) / 10;
        $intMax = (10 ** $length) - 1;

        return mt_rand($intMin, $intMax);
    }
}

if (!function_exists('extractRegionAndExchangeType')) {
    function extractRegionAndExchangeType($link)
    {
        $urlDetail = ['region' => 1, 'exchangeType' => 1];

        if (strpos($link, 'co.uk'))
            $urlDetail = ['region' => 3, 'exchangeType' => 2];
        elseif (strpos($link, '.fr'))
            $urlDetail = ['region' => 3, 'exchangeType' => 4];
        elseif (strpos($link, '.de'))
            $urlDetail = ['region' => 3, 'exchangeType' => 4];
        elseif (strpos($link, '.ae'))
            $urlDetail = ['region' => 2, 'exchangeType' => 3];
        elseif (strpos($link, '.tr'))
            $urlDetail = ['region' => 4, 'exchangeType' => 6];
        elseif (strpos($link, '.ca'))
            $urlDetail = ['region' => 1, 'exchangeType' => 5];

        return $urlDetail;
    }
}

if (!function_exists('truncate')) {
    function truncate($input, $maxWords, $maxChars)
    {
        $words = preg_split('/\s+/', $input);
        $words = array_slice($words, 0, $maxWords);
        $words = array_reverse($words);

        $chars = 0;
        $truncated = array();

        while (count($words) > 0) {
            $fragment = trim(array_pop($words));
            $chars += strlen($fragment);

            if ($chars > $maxChars) break;

            $truncated[] = $fragment;
        }

        $result = implode(' ', $truncated);
        if ($input == $result) {
            $ret = $input;
        } else {
            $ret = $result . '...';
        }
        return $ret;
    }
}


if (!function_exists('extractAsinAmazon')) {
    function extractAsinAmazon($url)
    {
        $pattern = '/(dp\/)([A-Z0-9]{10})/';
        preg_match($pattern, $url, $matches);
        if (isset($matches[2]))
            return $matches[2];
        return false;
    }
}

if (!function_exists('showDate')) {
    function showDate($date, $format = 'Y-m-d H:i:s')
    {
        return verta($date)->format($format);
    }
}

if (!function_exists('showAmount')) {
    function showAmount($amount, $format = 'IRR')
    {
        if (is_string($amount))
            return $amount;
        switch ($format) {
            case 'IRR':
                return number_format($amount, 0);
            default:
                return number_format($amount, 2);

        }
    }
}
if (!function_exists('createSlug')) {
    function createSlug($string, $separator = '-')
    {
        $_transliteration = ["/ö|œ/" => "e",
            "/ü/" => "e",
            "/Ä/" => "e",
            "/Ü/" => "e",
            "/Ö/" => "e",
            "/À|Á|Â|Ã|Å|Ǻ|Ā|Ă|Ą|Ǎ/" => "",
            "/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª/" => "",
            "/Ç|Ć|Ĉ|Ċ|Č/" => "",
            "/ç|ć|ĉ|ċ|č/" => "",
            "/Ð|Ď|Đ/" => "",
            "/ð|ď|đ/" => "",
            "/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě/" => "",
            "/è|é|ê|ë|ē|ĕ|ė|ę|ě/" => "",
            "/Ĝ|Ğ|Ġ|Ģ/" => "",
            "/ĝ|ğ|ġ|ģ/" => "",
            "/Ĥ|Ħ/" => "",
            "/ĥ|ħ/" => "",
            "/Ì|Í|Î|Ï|Ĩ|Ī| Ĭ|Ǐ|Į|İ/" => "",
            "/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı/" => "",
            "/Ĵ/" => "",
            "/ĵ/" => "",
            "/Ķ/" => "",
            "/ķ/" => "",
            "/Ĺ|Ļ|Ľ|Ŀ|Ł/" => "",
            "/ĺ|ļ|ľ|ŀ|ł/" => "",
            "/Ñ|Ń|Ņ|Ň/" => "",
            "/ñ|ń|ņ|ň|ŉ/" => "",
            "/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ/" => "",
            "/ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º/" => "",
            "/Ŕ|Ŗ|Ř/" => "",
            "/ŕ|ŗ|ř/" => "",
            "/Ś|Ŝ|Ş|Ș|Š/" => "",
            "/ś|ŝ|ş|ș|š|ſ/" => "",
            "/Ţ|Ț|Ť|Ŧ/" => "",
            "/ţ|ț|ť|ŧ/" => "",
            "/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ/" => "",
            "/ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ/" => "",
            "/Ý|Ÿ|Ŷ/" => "",
            "/ý|ÿ|ŷ/" => "",
            "/Ŵ/" => "",
            "/ŵ/" => "",
            "/Ź|Ż|Ž/" => "",
            "/ź|ż|ž/" => "",
            "/Æ|Ǽ/" => "E",
            "/ß/" => "s",
            "/Ĳ/" => "J",
            "/ĳ/" => "j",
            "/Œ/" => "E",
            "/ƒ/" => ""];
        $quotedReplacement = preg_quote($separator, '/');
        $merge = [
            '/[^\s\p{Zs}\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]/mu' => ' ',
            '/[\s\p{Zs}]+/mu' => $separator,
            sprintf('/^[%s]+|[%s]+$/', $quotedReplacement, $quotedReplacement) => '',
        ];
        $map = $_transliteration + $merge;
        unset($_transliteration);
        return preg_replace(array_keys($map), array_values($map), $string);

    }
}
