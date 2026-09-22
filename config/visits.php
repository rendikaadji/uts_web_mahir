<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Visit Anti-Duplicate Interval
    |--------------------------------------------------------------------------
    |
    | Number of minutes before the same visitor can record another visit
    | via QR scan. Subsequent scans within this window will return an
    | "already_checked_in" response without duplicating records.
    |
    */
    'duplicate_interval_minutes' => (int) env('VISIT_DUPLICATE_INTERVAL_MINUTES', 5),

    /*
    |--------------------------------------------------------------------------
    | Guest QR Token Expiry
    |--------------------------------------------------------------------------
    |
    | Duration in hours for which a guest session QR token remains valid.
    |
    */
    'guest_token_expiry_hours' => (int) env('GUEST_TOKEN_EXPIRY_HOURS', 24),
];
