<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\CreatesTestData;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase, CreatesTestData;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->createUser('Admin');
    }

    public function test_sales_report_page_loads(): void
    {
        $this->createTransactionWithDetail();

        $response = $this->actingAs($this->admin)->get(route('reports.sales'));

        $response->assertStatus(200);
        $response->assertViewHas(['transactions', 'total', 'start', 'end']);
    }

    public function test_sales_report_with_date_filter(): void
    {
        $response = $this->actingAs($this->admin)->get(route('reports.sales', [
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
        ]));

        $response->assertStatus(200);
    }

    public function test_sales_report_validates_date_format(): void
    {
        $response = $this->actingAs($this->admin)->get(route('reports.sales', [
            'start_date' => 'invalid-date',
        ]));

        $response->assertSessionHasErrors('start_date');
    }

    public function test_sales_report_validates_end_date_after_start_date(): void
    {
        $response = $this->actingAs($this->admin)->get(route('reports.sales', [
            'start_date' => '2026-12-31',
            'end_date' => '2026-01-01',
        ]));

        $response->assertSessionHasErrors('end_date');
    }

    public function test_sales_pdf_download(): void
    {
        $this->createTransactionWithDetail();

        $response = $this->actingAs($this->admin)->get(route('reports.sales.pdf'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_sales_excel_download(): void
    {
        $this->createTransactionWithDetail();

        $response = $this->actingAs($this->admin)->get(route('reports.sales.excel'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_kasir_cannot_access_reports(): void
    {
        $kasir = $this->createUser('Kasir');

        $response = $this->actingAs($kasir)->get(route('reports.sales'));

        $response->assertStatus(403);
    }

    public function test_gudang_cannot_access_reports(): void
    {
        $gudang = $this->createUser('Gudang');

        $response = $this->actingAs($gudang)->get(route('reports.sales'));

        $response->assertStatus(403);
    }

    public function test_owner_can_access_reports(): void
    {
        $owner = $this->createUser('Owner');

        $response = $this->actingAs($owner)->get(route('reports.sales'));

        $response->assertStatus(200);
    }

    public function test_sales_report_requires_authentication(): void
    {
        $response = $this->get(route('reports.sales'));

        $response->assertRedirect();
    }
}
