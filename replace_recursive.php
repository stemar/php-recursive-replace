<?php

/**
 * Recursively replace placeholders with $replacement values.
 */
function replace(string|array $subject, array $replacement, string $pattern='/\{\{(\w+)\}\}/'): mixed {
    if (is_array($subject)) {
        return array_map(fn($item) => replace($item, $replacement, $pattern), $subject);
    }
    $max = 10;
    do {
        $subject = preg_replace_callback($pattern, fn($matches) => $replacement[$matches[1]] ?? $matches[0], $subject, -1, $count);
        $max--;
    } while ($count > 0 && $max > 0);
    return $subject;
}
