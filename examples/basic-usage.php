<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Fbr\DigitalInvoicing\Facades\FbrDigitalInvoicing;
use Fbr\DigitalInvoicing\Builders\InvoiceBuilder;
use Fbr\DigitalInvoicing\Builders\InvoiceItemBuilder;
use Fbr\DigitalInvoicing\Constants\Scenarios;

class FbrTestController extends Controller
{
    

    public function test()
    {
        $results = [];
        
       
       
        try {
            // Create the same invoice as above
            $item = (new InvoiceItemBuilder())
            ->setHsCode('0101.2100')
            ->setProductDescription('TEST')
            ->setRate('18%')
            ->setUom('Numbers, pieces, units')
            ->setQuantity(1.0)
            ->setTotalValues(0)
            ->setValueSalesExcludingST(1000)
            ->setFixedNotifiedValueOrRetailPrice(0)
            ->setSalesTaxApplicable(180)
            ->setSalesTaxWithheldAtSource(0)
            ->setExtraTax(0)
            ->setFurtherTax(0)
            ->setFedPayable(0)
            ->setDiscount(0)
            ->setSaleType('Goods at standard rate (default)')
            ->setSroItemSerialNo('')
            ->build();
        
        $invoice = (new InvoiceBuilder())
            ->setInvoiceType('Sale Invoice')
            ->setInvoiceDate('2025-08-05')
            ->setSeller('5076033', 'Company 8', 'Sindh', 'Karachi')
            ->setBuyer('1350439930769', 'FERTILIZER MANUFAC IRS NEW', 'Sindh', 'Karachi', 'Unregistered')
            ->setScenarioId('SN002')
            ->setInvoiceRefNo('SI-20250421-001')
            ->addItem($item)
            ->build();

            // Submit to FBR via Facade (as per README)
            $response = FbrDigitalInvoicing::postInvoiceData($invoice);

                $results['invoice_submission'] = [
                    'status' => $response->isValid() ? 'success' : 'error',
                    'submission_attempted' => true,
                    'response_valid' => $response->isValid(),
                    'message' => $response->isValid()
                        ? 'Invoice submitted successfully'
                        : 'Submission validation failed',
                    'details' => [
                        'invoiceNumber' => $response->invoiceNumber,
                        'dated' => $response->dated,
                        'validation' => [
                            'statusCode' => $response->validationResponse->statusCode,
                            'status' => $response->validationResponse->status,
                            'errorCode' => $response->validationResponse->errorCode,
                            'error' => $response->validationResponse->error,
                            'invoiceStatuses' => collect($response->validationResponse->invoiceStatuses)->map(function ($status) {
                                return [
                                    'itemSNo' => $status->itemSNo,
                                    'statusCode' => $status->statusCode,
                                    'status' => $status->status,
                                    'invoiceNo' => $status->invoiceNo,
                                    'errorCode' => $status->errorCode,
                                    'error' => $status->error,
                                ];
                            })->toArray()
                        ]
                    ]
                ];
            
        } catch (\Exception $e) {
            $results['invoice_submission'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
        
       
        
        return response()->json([
            'message' => 'FBR Digital Invoicing Package Test Results',
            'package_installed' => true,
            'test_results' => $results
        ]);
    }
}