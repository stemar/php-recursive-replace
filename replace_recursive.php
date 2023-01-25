<?php

/**
 * Recursively replace placeholders with $replacement values.
 *
 * @param string $subject String or an array with strings to search and replace
 * @param array $replacement String or an array with strings to replace
 * @param regex $pattern
 * @return mixed
 */
function replace($subject, $replacement, $pattern='/\{\{(\w+)\}\}/') {
    if (is_array($subject)) {
        $func = __FUNCTION__;
        return array_map(function ($subject) use ($func, $replacement, $pattern) {
            return call_user_func($func, $subject, $replacement, $pattern);
        }, $subject);
    }
    while (preg_match($pattern, $subject)) {
        $subject = preg_replace_callback($pattern, function ($matches) use ($replacement) {
            return @$replacement[$matches[1]] ?: $matches[0];
        }, $subject);
    }
    return $subject;
}
