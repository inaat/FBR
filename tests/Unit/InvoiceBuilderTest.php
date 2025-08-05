
<?php 
namespace Fbr\DigitalInvoicing\Tests\Unit;

use Fbr\DigitalInvoicing\Builders\InvoiceBuilder;
use Fbr\DigitalInvoicing\Builders\InvoiceItemBuilder;
use Fbr\DigitalInvoicing\Tests\TestCase;

class InvoiceBuilderTest extends TestCase
{
    public function test_can_build_invoice()
    {
        $item = (new InvoiceItemBuilder())
            ->setHsCode('0101.2100')
            ->setProductDescription('Test Product')
            ->setRate('18%')
            ->setUom('Numbers, pieces, units')
            ->setQuantity(1.0)
            ->setTotalValues(1180.0)
            ->setValueSalesExcludingST(1000.0)
            ->setFixedNotifiedValueOrRetailPrice(0.0)
            ->setSalesTaxApplicable(180.0)
            ->setSalesTaxWithheldAtSource(0.0)
            ->setExtraTax(0.0)
            ->setFurtherTax(0.0)
            ->setSroScheduleNo('')
            ->setFedPayable(0.0)
            ->setDiscount(0.0)
            ->setSaleType('Goods at standard rate (default)')
            ->setSroItemSerialNo('')
            ->build();

        $invoice = (new InvoiceBuilder())
            ->setInvoiceType('Sale Invoice')
            ->setInvoiceDate('2025-08-04')
            ->setSeller('0786909', 'Test Company', 'Sindh', 'Karachi')
            ->setBuyer('1000000000000', 'Test Buyer', 'Sindh', 'Karachi', 'Registered')
            ->setInvoiceRefNo('')
            ->setScenarioId('SN001')
            ->addItem($item)
            ->build();

        $this->assertIsArray($invoice);
        $this->assertEquals('Sale Invoice', $invoice['invoiceType']);
        $this->assertEquals('2025-08-04', $invoice['invoiceDate']);
        $this->assertEquals('0786909', $invoice['sellerNTNCNIC']);
        $this->assertCount(1, $invoice['items']);
    }
}