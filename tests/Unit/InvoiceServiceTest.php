<?php

namespace Tests\Unit;

use App\Services\InvoiceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_generates_invoice_with_correct_format(): void
    {
        $invoice = InvoiceService::generateTransactionInvoice();
        $this->assertStringStartsWith('INV-', $invoice);
        $this->assertEquals(17, strlen($invoice)); // INV-YYYYMMDD-NNNN
    }

    public function test_generates_purchase_code(): void
    {
        $code = InvoiceService::generatePurchaseCode();
        $this->assertStringStartsWith('PO-', $code);
    }
}
