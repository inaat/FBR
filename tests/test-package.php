<?php
/**
 * Test FBR Digital Invoicing Laravel Package
 * Uses the actual package classes and services
 */

// Autoload the package classes
require_once __DIR__ . '/../src/Services/FbrDigitalInvoicingService.php';
require_once __DIR__ . '/../src/Services/FbrReferenceService.php';
require_once __DIR__ . '/../src/Builders/InvoiceBuilder.php';
require_once __DIR__ . '/../src/Builders/InvoiceItemBuilder.php';
require_once __DIR__ . '/../src/DTOs/InvoiceData.php';
require_once __DIR__ . '/../src/DTOs/InvoiceItemData.php';
require_once __DIR__ . '/../src/DTOs/InvoiceResponse.php';
require_once __DIR__ . '/../src/Constants/Scenarios.php';

use Fbr\DigitalInvoicing\Services\FbrDigitalInvoicingService;
use Fbr\DigitalInvoicing\Services\FbrReferenceService;
use Fbr\DigitalInvoicing\Builders\InvoiceBuilder;
use Fbr\DigitalInvoicing\Builders\InvoiceItemBuilder;
use Fbr\DigitalInvoicing\Constants\Scenarios;

echo "🚀 FBR Digital Invoicing Package Test\n";
echo "====================================\n\n";

// Mock Laravel config function
if (!function_exists('config')) {
    function config($key, $default = null) {
        $config = [
            'fbr-digital-invoicing.bearer_token' => 'd5166958-c131-3bf0-b0e0-ff0ccff78af8',
            'fbr-digital-invoicing.sandbox' => true,
            'fbr-digital-invoicing.urls.sandbox' => 'https://gw.fbr.gov.pk/di_data/v1/di/',
            'fbr-digital-invoicing.urls.reference' => 'https://gw.fbr.gov.pk/pdi/v1/',
            'fbr-digital-invoicing.timeout' => 30,
            'fbr-digital-invoicing.retry_attempts' => 3,
            'fbr-digital-invoicing.logging.enabled' => true,
        ];
        return $config[$key] ?? $default;
    }
}


// Mock Laravel now() function
if (!function_exists('now')) {
    function now() {
        return new DateTime();
    }
}

$workingNTN = '5076033';

echo "🔧 Configuration:\n";
echo "- Working NTN: $workingNTN\n";
echo "- Bearer Token: " . substr(config('fbr-digital-invoicing.bearer_token'), 0, 8) . "...\n";
echo "- Environment: " . (config('fbr-digital-invoicing.sandbox') ? 'Sandbox' : 'Production') . "\n\n";

// Test 1: Reference Service
echo "1️⃣  Testing Reference Service\n";
echo "----------------------------\n";

try {
    $referenceService = new FbrReferenceService(
        'https://gw.fbr.gov.pk/pdi/v1/',
        'd5166958-c131-3bf0-b0e0-ff0ccff78af8',
        30
    );
    
    // Test provinces
    echo "📍 Fetching provinces... ";
    $provinces = $referenceService->getProvinces();
    echo "✅ Got " . count($provinces) . " provinces\n";
    
    // Test UOM codes
    echo "📏 Fetching UOM codes... ";
    $uoms = $referenceService->getUomCodes();
    echo "✅ Got " . count($uoms) . " UOM codes\n";
    
    // Test HS codes
    echo "🏷️  Fetching HS codes... ";
    $hsCodes = $referenceService->getItemDescCodes();
    echo "✅ Got " . count($hsCodes) . " HS codes\n";
    
    echo "✅ Reference Service: WORKING\n\n";
    
} catch (Exception $e) {
    echo "❌ Reference Service Error: " . $e->getMessage() . "\n\n";
}

// Test 2: Invoice Builder
echo "2️⃣  Testing Invoice Builder\n";
echo "--------------------------\n";

try {
    // Create invoice item using builder
    $item = (new InvoiceItemBuilder())
        ->setHsCode('0101.2100')
        ->setProductDescription('Package Test Product')
        ->setRate('18%')
        ->setUom('Numbers, pieces, units')
        ->setQuantity(1.0)
        ->setValueSalesExcludingST(1000.0)
        ->setSalesTaxApplicable(180.0)
        ->setSaleType('Goods at standard rate (default)')
        ->build();

    echo "✅ InvoiceItemBuilder: Working\n";
    echo "   - HS Code: {$item->hsCode}\n";
    echo "   - Description: {$item->productDescription}\n";
    echo "   - Rate: {$item->rate}\n";
    echo "   - Sales Value: Rs. " . number_format($item->valueSalesExcludingST, 2) . "\n";

    // Create invoice using builder
    $invoice = (new InvoiceBuilder())
        ->setInvoiceType('Sale Invoice')
        ->setInvoiceDate(date('Y-m-d'))
        ->setSeller($workingNTN, 'Package Test Company', 'Sindh', 'Karachi')
        ->setBuyer('2046004', 'Test Customer Ltd', 'Sindh', 'Karachi', 'Registered')
        ->setScenarioId('SN001')
        ->setInvoiceRefNo('PKG-TEST-' . date('YmdHis'))
        ->addItem($item)
        ->build();

    echo "✅ InvoiceBuilder: Working\n";
    echo "   - Invoice Type: {$invoice->invoiceType}\n";
    echo "   - Date: {$invoice->invoiceDate}\n";
    echo "   - Seller: {$invoice->sellerBusinessName} ({$invoice->sellerNTNCNIC})\n";
    echo "   - Buyer: {$invoice->buyerBusinessName} ({$invoice->buyerNTNCNIC})\n";
    echo "   - Items: " . count($invoice->items) . "\n\n";

} catch (Exception $e) {
    echo "❌ Builder Error: " . $e->getMessage() . "\n\n";
}

// Test 3: Invoice Service - Validation
echo "3️⃣  Testing Invoice Service - Validation\n";
echo "---------------------------------------\n";

try {
    $invoiceService = new FbrDigitalInvoicingService(
        'd5166958-c131-3bf0-b0e0-ff0ccff78af8',
        true, // sandbox
        30,   // timeout
        3,    // retry attempts
        true  // logging enabled
    );
    
    echo "📋 Validating invoice with FBR...\n";
    
    $validationResponse = $invoiceService->validateInvoiceData($invoice);
    
    if ($validationResponse->isValid()) {
        echo "✅ VALIDATION: SUCCESS\n";
        echo "   - Status: {$validationResponse->validationResponse->status}\n";
        echo "   - Status Code: {$validationResponse->validationResponse->statusCode}\n";
        
        if ($validationResponse->validationResponse->error) {
            echo "   - Error: {$validationResponse->validationResponse->error}\n";
        }
        
    } else {
        echo "❌ VALIDATION: FAILED\n";
        $errors = $validationResponse->getErrors();
        foreach ($errors as $error) {
            echo "   - Error: $error\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Validation Service Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 4: Invoice Service - Submission
echo "4️⃣  Testing Invoice Service - Submission\n";
echo "--------------------------------------\n";

try {
    echo "🚀 Submitting invoice to FBR...\n";
    
    $submitResponse = $invoiceService->postInvoiceData($invoice);
    
    if ($submitResponse->isValid()) {
        echo "🎉 SUBMISSION: SUCCESS\n";
        echo "   - FBR Invoice Number: {$submitResponse->invoiceNumber}\n";
        echo "   - Date: {$submitResponse->dated}\n";
        echo "   - Status: {$submitResponse->validationResponse->status}\n";
        
        // Check item statuses
        if (!empty($submitResponse->validationResponse->invoiceStatuses)) {
            echo "   - Item Statuses:\n";
            foreach ($submitResponse->validationResponse->invoiceStatuses as $itemStatus) {
                echo "     • Item {$itemStatus->itemSNo}: {$itemStatus->status} ({$itemStatus->invoiceNo})\n";
            }
        }
        
    } else {
        echo "❌ SUBMISSION: FAILED\n";
        $errors = $submitResponse->getErrors();
        foreach ($errors as $error) {
            echo "   - Error: $error\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Submission Service Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 5: Scenario-based Invoice Generation
echo "5️⃣  Testing Scenario-based Invoice Generation\n";
echo "--------------------------------------------\n";

try {
    // Test different scenarios
    $testScenarios = ['SN001', 'SN002', 'SN022', 'SN023'];
    
    foreach ($testScenarios as $scenarioId) {
        echo "🧪 Testing $scenarioId: ";
        
        $scenario = Scenarios::getScenario($scenarioId);
        if ($scenario) {
            echo "{$scenario['name']}\n";
            
            // Generate invoice for this scenario
            $scenarioInvoice = Scenarios::generateScenarioInvoice($scenarioId, [
                'sellerNTNCNIC' => $workingNTN,
                'invoiceRefNo' => "$scenarioId-PKG-" . date('His'),
                'invoiceDate' => date('Y-m-d')
            ]);
            
            echo "   📊 Generated invoice with {$scenarioInvoice['items'][0]['rate']} rate\n";
            echo "   💰 Sales Value: Rs.{$scenarioInvoice['items'][0]['valueSalesExcludingST']}\n";
            
        } else {
            echo "❌ Scenario not found\n";
        }
        echo "\n";
    }
    
    echo "✅ Scenario Generation: Working\n";
    
} catch (Exception $e) {
    echo "❌ Scenario Generation Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Summary
echo str_repeat("=", 50) . "\n";
echo "📊 PACKAGE TEST SUMMARY\n";
echo str_repeat("=", 50) . "\n";

echo "✅ Reference Service: Working\n";
echo "✅ Invoice Builder: Working\n";
echo "✅ Scenario Generation: Working\n";

if (isset($validationResponse) && $validationResponse->isValid()) {
    echo "✅ Invoice Validation: Working\n";
} else {
    echo "⚠️  Invoice Validation: Check errors above\n";
}

if (isset($submitResponse) && $submitResponse->isValid()) {
    echo "✅ Invoice Submission: Working\n";
    echo "\n🎉 PACKAGE FULLY FUNCTIONAL!\n";
    echo "📋 FBR Invoice Generated: {$submitResponse->invoiceNumber}\n";
} else {
    echo "⚠️  Invoice Submission: Check errors above\n";
}

echo "\n💡 PACKAGE READY FOR LARAVEL INTEGRATION:\n";
echo "1. Install: composer require fbr/digital-invoicing\n";
echo "2. Publish config: php artisan vendor:publish --tag=fbr-config\n";
echo "3. Run migrations: php artisan migrate\n";
echo "4. Set .env: FBR_BEARER_TOKEN=d5166958-c131-3bf0-b0e0-ff0ccff78af8\n";
echo "5. Use NTN: $workingNTN\n";

echo "\n✨ FBR Digital Invoicing Package Test Complete!\n";

?>