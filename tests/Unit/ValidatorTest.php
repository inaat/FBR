<?php
namespace Fbr\DigitalInvoicing\Tests\Unit;

use Fbr\DigitalInvoicing\Validators\InvoiceValidator;
use Fbr\DigitalInvoicing\Exceptions\FbrDigitalInvoicingException;
use Fbr\DigitalInvoicing\Tests\TestCase;

class ValidatorTest extends TestCase
{
    public function test_validates_required_fields()
    {
        $this->expectException(FbrDigitalInvoicingException::class);
        
        InvoiceValidator::validate([]);
    }

    public function test_validates_ntn_format()
    {
        $this->expectException(FbrDigitalInvoicingException::class);
        $this->expectExceptionMessage('Seller NTN/CNIC must be 7 digits');

        $invoiceData = [
            'invoiceType' => 'Sale Invoice',
            'invoiceDate' => '2025-08-04',
            'sellerNTNCNIC' => '123', // Invalid NTN
            'sellerBusinessName' => 'Test',
            'sellerProvince' => 'Sindh',
            'sellerAddress' => 'Karachi',
            'buyerBusinessName' => 'Test Buyer',
            'buyerProvince' => 'Sindh',
            'buyerAddress' => 'Karachi',
            'buyerRegistrationType' => 'Registered',
            'items' => []
        ];

        InvoiceValidator::validate($invoiceData);
    }

    public function test_validates_date_format()
    {
        $this->expectException(FbrDigitalInvoicingException::class);
        $this->expectExceptionMessage('Invalid date format');

        $invoiceData = [
            'invoiceType' => 'Sale Invoice',
            'invoiceDate' => '04-08-2025', // Invalid format
            'sellerNTNCNIC' => '0786909',
            'sellerBusinessName' => 'Test',
            'sellerProvince' => 'Sindh',
            'sellerAddress' => 'Karachi',
            'buyerBusinessName' => 'Test Buyer',
            'buyerProvince' => 'Sindh',
            'buyerAddress' => 'Karachi',
            'buyerRegistrationType' => 'Registered',
            'items' => [
                [
                    'hsCode' => '0101.2100',
                    'productDescription' => 'Test',
                    'rate' => '18%',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 1.0,
                    'totalValues' => 1000.0,
                    'valueSalesExcludingST' => 1000.0,
                    'fixedNotifiedValueOrRetailPrice' => 0.0,
                    'salesTaxApplicable' => 180.0,
                    'salesTaxWithheldAtSource' => 0.0,
                    'saleType' => 'Goods at standard rate (default)'
                ]
            ]
        ];

        InvoiceValidator::validate($invoiceData);
    }
}