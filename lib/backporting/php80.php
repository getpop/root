<?php
/**
 * Backporting for PHP 8.0 functions.
 * Implement functions `str_contains`, `str_starts_with` and `str_ends_with`
 * if not already implemented by some library
 */

 /**
 * PHP_VERSION_ID is available as of PHP 5.2.7
 * PHP_VERSION_ID 80000 => PHP 8.0
 */
if (!defined('PHP_VERSION_ID') || (defined('PHP_VERSION_ID') && PHP_VERSION_ID < 80000)) {
    if (!function_exists('str_contains')) {
        /**
         * Checks if a string contains another
         *
         * @param string $haystack The string to search in
         * @param string $needle The string to search
         * @return boolean Returns TRUE if the needle was found in haystack, FALSE otherwise.
         */
        function str_contains(string $haystack, string $needle): bool
        {
            return strpos($haystack, $needle) !== false;
        }
    }

    if (!function_exists('str_starts_with')) {
        /**
         * Checks if haystack starts with needle
         *
         * @param string $haystack The string to search in
         * @param string $needle The string to search
         * @return boolean Returns TRUE if the haystack starts with the needle, FALSE otherwise.
         */
        function str_starts_with(string $haystack, string $needle): bool
        {
            return substr($haystack, 0, strlen($needle)) === $needle;
        }
    }

    if (!function_exists('str_ends_with')) {
        /**
         * Checks if haystack ends with needle
         *
         * @param string $haystack The string to search in
         * @param string $needle The string to search
         * @return boolean Returns TRUE if the haystack ends with the needle, FALSE otherwise.
         */
        function str_ends_with(string $haystack, string $needle): bool
        {
            return substr($haystack, -strlen($needle)) === $needle;
        }
    }
}
