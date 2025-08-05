<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Fbr\DigitalInvoicing\Facades\FbrDigitalInvoicing;
use Fbr\DigitalInvoicing\Builders\InvoiceBuilder;
use Fbr\DigitalInvoicing\Builders\InvoiceItemBuilder;
use Fbr\DigitalInvoicing\Models\Invoice;
use Fbr\DigitalInvoicing\Jobs\SubmitInvoiceJob;

/**
 * Basic Usage Examples for FBR Digital Invoicing Package
 */

// Example 1: Create and submit a simple invoice
function createBasicInvoice()
{
    // Create invoice item
    $item = (new InvoiceItemBuilder())
        ->setHsCode('0101.2100')
        ->setProductDescription('Test Product')
        ->setRate('18%')
        ->setUom('Numbers, pieces, units')
        ->setQuantity(1.0)
        ->setValueSalesExcludingST(1000.0)
        ->setSalesTaxApplicable(180.0)
        ->setSaleType('Goods at standard rate (default)')
        ->build();

    // Create invoice
    $invoice = (new InvoiceBuilder())
        ->setInvoiceType('Sale Invoice')
        ->setInvoiceDate('2025-08-04')
        ->setSeller('0786909', 'My Company', 'Sindh', 'Karachi')
        ->setBuyer('1000000000000', 'Customer Ltd', 'Punjab', 'Lahore', 'Registered')
        ->setScenarioId('SN001') // Only for sandbox
        ->addItem($item)
        ->build();

    // Submit to FBR
    try {
        $response = FbrDigitalInvoicing::postInvoiceData($invoice);
        
        if ($response->isValid()) {
            echo "✅ Invoice submitted successfully!\n";
            echo "FBR Invoice Number: " . $response->invoiceNumber . "\n";
            echo "Date: " . $response->dated . "\n";
        } else {
            echo "❌ Invoice submission failed:\n";
            foreach ($response->getErrors() as $error) {
                echo "- " . $error . "\n";
            }
        }
    } catch (\Exception $e) {
        echo "❌ Exception: " . $e->getMessage() . "\n";
    }
}

// Example 2: Validate invoice before submission
function validateInvoiceFirst()
{
    $item = (new InvoiceItemBuilder())
        ->setHsCode('0101.2100')
        ->setProductDescription('Validation Test Product')
        ->setRate('18%')
        ->setUom('Numbers, pieces, units')
        ->setQuantity(5.0)
        ->setValueSalesExcludingST(5000.0)
        ->setSalesTaxApplicable(900.0)
        ->setSaleType('Goods at standard rate (default)')
        ->build();

    $invoice = (new InvoiceBuilder())
        ->setInvoiceType('Sale Invoice')
        ->setInvoiceDate('2025-08-04')
        ->setSeller('0786909', 'My Company', 'Sindh', 'Karachi')
        ->setBuyer('2046004', 'Another Customer', 'Punjab', 'Lahore', 'Registered')
        ->setScenarioId('SN001')
        ->addItem($item)
        ->build();

    // First validate
    $validationResponse = FbrDigitalInvoicing::validateInvoiceData($invoice);
    
    if ($validationResponse->isValid()) {
        echo "✅ Validation successful! Submitting invoice...\n";
        
        // Now submit
        $submitResponse = FbrDigitalInvoicing::postInvoiceData($invoice);
        
        if ($submitResponse->isValid()) {
            echo "✅ Invoice submitted successfully!\n";
            echo "FBR Invoice Number: " . $submitResponse->invoiceNumber . "\n";
        } else {
            echo "❌ Submission failed after validation:\n";
            foreach ($submitResponse->getErrors() as $error) {
                echo "- " . $error . "\n";
            }
        }
    } else {
        echo "❌ Validation failed:\n";
        foreach ($validationResponse->getErrors() as $error) {
            echo "- " . $error . "\n";
        }
    }
}

// Example 3: Using database models and queue jobs
function useModelsAndJobs()
{
    // Create invoice item data
    $itemData = (new InvoiceItemBuilder())
        ->setHsCode('0101.2100')
        ->setProductDescription('Queued Product')
        ->setRate('18%')
        ->setUom('Numbers, pieces, units')
        ->setQuantity(2.0)
        ->setValueSalesExcludingST(2000.0)
        ->setSalesTaxApplicable(360.0)
        ->setSaleType('Goods at standard rate (default)')
        ->build();

    // Create invoice data
    $invoiceData = (new InvoiceBuilder())
        ->setInvoiceType('Sale Invoice')
        ->setInvoiceDate('2025-08-04')
        ->setSeller('0786909', 'My Company', 'Sindh', 'Karachi')
        ->setBuyer('1000000000000', 'Queue Customer', 'Punjab', 'Lahore', 'Registered')
        ->setScenarioId('SN001')
        ->addItem($itemData)
        ->build();

    // Save to database
    $invoice = Invoice::createFromInvoiceData($invoiceData);
    
    echo "💾 Invoice saved to database with ID: " . $invoice->id . "\n";
    echo "📤 Queueing for submission...\n";
    
    // Queue for submission
    SubmitInvoiceJob::dispatch($invoice)->onQueue('fbr-invoices');
    
    echo "✅ Invoice queued for background processing!\n";
}

// Example 4: Get reference data
function getReferenceData()
{
    echo "📊 Fetching reference data...\n\n";
    
    // Get provinces
    $provinces = FbrDigitalInvoicing::getProvinces();
    echo "🗺️  Provinces (" . count($provinces) . "):\n";
    foreach (array_slice($provinces, 0, 3) as $province) {
        echo "- {$province['stateProvinceDesc']} (Code: {$province['stateProvinceCode']})\n";
    }
    echo "\n";
    
    // Get UOM codes
    $uoms = FbrDigitalInvoicing::getUomCodes();
    echo "📏 Unit of Measurements (" . count($uoms) . ", showing first 3):\n";
    foreach (array_slice($uoms, 0, 3) as $uom) {
        echo "- {$uom['description']} (ID: {$uom['uoM_ID']})\n";
    }
    echo "\n";
    
    // Get document types
    $docTypes = FbrDigitalInvoicing::getDocumentTypeCodes();
    echo "📄 Document Types (" . count($docTypes) . "):\n";
    foreach ($docTypes as $docType) {
        echo "- {$docType['docDescription']} (ID: {$docType['docTypeId']})\n";
    }
    echo "\n";
}

// Example 5: Create invoice for different scenarios
function createScenarioBasedInvoices()
{
    $scenarios = [
        'SN001' => 'Standard Rate to Registered Buyers',
        'SN002' => 'Standard Rate to Unregistered Buyers',
        'SN005' => 'Reduced Rate Goods',
        'SN006' => 'Exempt Goods',
        'SN007' => 'Zero-Rated Goods'
    ];
    
    foreach ($scenarios as $scenarioId => $description) {
        echo "📋 Creating invoice for {$scenarioId}: {$description}\n";
        
        $item = (new InvoiceItemBuilder())
            ->setHsCode('0101.2100')
            ->setProductDescription("Product for {$scenarioId}")
            ->setRate($scenarioId === 'SN005' ? '1%' : ($scenarioId === 'SN006' ? 'Exempt' : ($scenarioId === 'SN007' ? '0%' : '18%')))
            ->setUom('Numbers, pieces, units')
            ->setQuantity(1.0)
            ->setValueSalesExcludingST(1000.0)
            ->setSalesTaxApplicable($scenarioId === 'SN005' ? 10.0 : ($scenarioId === 'SN006' || $scenarioId === 'SN007' ? 0.0 : 180.0))
            ->setSaleType('Goods at standard rate (default)')
            ->build();

        $invoice = (new InvoiceBuilder())
            ->setInvoiceType('Sale Invoice')
            ->setInvoiceDate('2025-08-04')
            ->setSeller('0786909', 'My Company', 'Sindh', 'Karachi')
            ->setBuyer('1000000000000', 'Customer Ltd', 'Punjab', 'Lahore', 'Registered')
            ->setScenarioId($scenarioId)
            ->addItem($item)
            ->build();
        
        // Save to database for later processing
        $dbInvoice = Invoice::createFromInvoiceData($invoice);
        echo "✅ Invoice created with ID: {$dbInvoice->id}\n\n";
    }
}

// Example 6: Check STATL registration
function checkRegistrationStatus()
{
    $ntnToCheck = '0786909';
    $date = '2025-08-04';
    
    echo "🔍 Checking STATL status for NTN: {$ntnToCheck}\n";
    
    try {
        $statlResponse = FbrDigitalInvoicing::checkStatl($ntnToCheck, $date);
        echo "📊 STATL Status: " . ($statlResponse['status'] ?? 'Unknown') . "\n";
        echo "📋 Status Code: " . ($statlResponse['status code'] ?? 'Unknown') . "\n";
        
        // Also check registration type
        $regTypeResponse = FbrDigitalInvoicing::getRegistrationType($ntnToCheck);
        echo "🏢 Registration Type: " . ($regTypeResponse['REGISTRATION_TYPE'] ?? 'Unknown') . "\n";
        echo "✅ Registration Status Code: " . ($regTypeResponse['statuscode'] ?? 'Unknown') . "\n";
        
    } catch (\Exception $e) {
        echo "❌ Error checking status: " . $e->getMessage() . "\n";
    }
}

// Run examples
echo "🚀 FBR Digital Invoicing Package - Usage Examples\n";
echo "================================================\n\n";

echo "1️⃣  Creating and submitting basic invoice:\n";
createBasicInvoice();
echo "\n";

echo "2️⃣  Validating before submission:\n";
validateInvoiceFirst();
echo "\n";

echo "3️⃣  Using models and background jobs:\n";
useModelsAndJobs();
echo "\n";

echo "4️⃣  Getting reference data:\n";
getReferenceData();
echo "\n";

echo "5️⃣  Creating scenario-based invoices:\n";
createScenarioBasedInvoices();
echo "\n";

echo "6️⃣  Checking registration status:\n";
checkRegistrationStatus();
echo "\n";

echo "✅ All examples completed!\n";
echo "\n📝 Next steps:\n";
echo "- Configure your .env with FBR_BEARER_TOKEN\n";
echo "- Run 'php artisan migrate' to create database tables\n";
echo "- Run 'php artisan fbr:sync' to cache reference data\n";
echo "- Set up queue workers for background processing\n";
echo "- Check logs for detailed API interactions\n";