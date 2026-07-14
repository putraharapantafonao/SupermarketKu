<?php

namespace App\Services;

class InvoiceService
{
    public static function generateTransactionInvoice(): string
    {
        $now = now();
        $prefix = 'INV-' . $now->format('Ymd');
        $micro = substr((string) microtime(true), -4);

        return $prefix . '-' . str_pad($micro, 4, '0', STR_PAD_LEFT);
    }

    public static function generatePurchaseCode(): string
    {
        return 'PO-' . now()->format('YmdHis') . '-' . random_int(100, 999);
    }
}
