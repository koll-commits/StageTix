<?php

// Tarif platform (0.1000 = 10%). Bisa diubah lewat .env.
return [
    'tax_rate' => (float) env('STAGETIX_TAX_RATE', 0.1000),
    'service_fee_rate' => (float) env('STAGETIX_SERVICE_FEE_RATE', 0.0200),
];