<?php

if (!defined('MB_CASE_UPPER')) {
    define('MB_CASE_UPPER', 0);
    define('MB_CASE_LOWER', 1);
    define('MB_CASE_TITLE', 2);
    define('MB_CASE_FOLD', 3);
}

if (!function_exists('mb_strlen')) {
    function mb_strlen(string $string, ?string $encoding = null): int
    {
        return count(preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY) ?: []);
    }
}

if (!function_exists('mb_substr')) {
    function mb_substr(string $string, int $start, ?int $length = null, ?string $encoding = null): string
    {
        $characters = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
        if ($characters === false) {
            return '';
        }
        return implode('', array_slice($characters, $start, $length));
    }
}

if (!function_exists('mb_strtolower')) {
    function mb_strtolower(string $string, ?string $encoding = null): string
    {
        return function_exists('mb_convert_case')
            ? mb_convert_case($string, MB_CASE_LOWER, $encoding)
            : strtolower($string);
    }
}

if (!function_exists('mb_convert_case')) {
    function mb_convert_case(string $string, int $mode, ?string $encoding = null): string
    {
        if ($mode === MB_CASE_LOWER) {
            return strtolower($string);
        }
        if ($mode === MB_CASE_UPPER) {
            return strtoupper($string);
        }
        if ($mode === MB_CASE_TITLE) {
            return ucwords(strtolower($string));
        }
        return strtolower($string);
    }
}

if (!function_exists('mb_chr')) {
    function mb_chr(int $codepoint, ?string $encoding = null): string
    {
        return html_entity_decode('&#' . $codepoint . ';', ENT_NOQUOTES | ENT_HTML5, 'UTF-8');
    }
}

if (!function_exists('mb_str_split')) {
    function mb_str_split(string $string, int $length = 1, ?string $encoding = null): array
    {
        if ($length < 1) {
            $length = 1;
        }
        $characters = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
        if ($characters === false) {
            return [];
        }
        $result = [];
        foreach (array_chunk($characters, $length) as $chunk) {
            $result[] = implode('', $chunk);
        }
        return $result;
    }
}

if (!function_exists('mb_check_encoding')) {
    function mb_check_encoding($value = null, ?string $encoding = null): bool
    {
        return $value === null || preg_match('//u', (string) $value) === 1;
    }
}

if (!function_exists('mb_convert_encoding')) {
    function mb_convert_encoding(string $string, string $toEncoding, $fromEncoding = null): string
    {
        return function_exists('iconv')
            ? (iconv((string) $fromEncoding, $toEncoding . '//IGNORE', $string) ?: '')
            : $string;
    }
}

if (!function_exists('mb_substitute_character')) {
    function mb_substitute_character($substituteCharacter = null)
    {
        static $character = 0xFFFD;
        if ($substituteCharacter !== null) {
            $character = $substituteCharacter;
            return true;
        }
        return $character;
    }
}
