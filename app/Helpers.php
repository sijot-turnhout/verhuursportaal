<?php

declare(strict_types=1);

if (! function_exists('toHumanReadableNumber')) {
    /**
     * Convert an integer into a human-readable formatted number with commas.
     *
     * @param  int $number  The number to be formatted.
     * @return string       The formatted number as a string.
     */
    function toHumanReadableNumber(int $number): string {
        return number_format($number);
    }
}

if (! function_exists('toHumanReadablePercentage')) {
    /**
     * Convert two integers (part and total) into a human-readable percentage.
     * If the total is 0, it returns "Infinity%" to avoid division by zero.
     *
     * @param  int|string $total  The total value (denominator).
     * @param  int|string $part   The part value (numerator).
     * @return string             The calculated percentage, formatted to 1 decimal place.
     */
    function toHumanReadablePercentage(int|string $total, int|string $part): string {
        if ($total === 0) {
            return 'Infinity%';
        }

        /** @phpstan-ignore-next-line */
        return number_format($part / $total * 100, 1).'%';
    }
}
