<?php

if (! function_exists('formatRupiah')) {
    /**
     * Format a numeric amount as Indonesian Rupiah.
     */
    function formatRupiah(mixed $amount): string
    {
        return 'Rp ' . number_format((float) $amount, 0, ',', '.');
    }
}
