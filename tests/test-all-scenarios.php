<?php
/**
 * Test all 28 FBR scenarios with working NTN 5076033
 * Uses official FBR scenario data from Scenarios.php
 */

require_once __DIR__ . '/../src/Constants/Scenarios.php';
use Fbr\DigitalInvoicing\Constants\Scenarios;

echo "🎯 FBR Digital Invoicing - All 28 Scenarios Test\n";
echo "===============================================\n\n";

$bearerToken = 'd5166958-c131-3bf0-b0e0-ff0ccff78af8';
$workingNTN = '5076033';

// Get all scenarios
$allScenarios = Scenarios::getAllScenarios();

$results = [];
$successCount = 0;
$totalCount = count($allScenarios);

echo "📋 Testing $totalCount scenarios with NTN: $workingNTN\n";
echo "🔑 Bearer Token: " . substr($bearerToken, 0, 8) . "...\n\n";

foreach ($allScenarios as $scenarioId => $scenario) {
    echo "🧪 Testing $scenarioId: {$scenario['name']}\n";
    
    // Generate invoice using official FBR scenario data
    try {
        $invoiceData = Scenarios::generateScenarioInvoice($scenarioId);
        
        // Override with our working NTN
        $invoiceData['sellerNTNCNIC'] = $workingNTN;
        
        // Add unique reference to avoid duplicates
        $invoiceData['invoiceRefNo'] = $scenarioId . '-' . date('YmdHis') . '-' . rand(1000, 9999);
        
        // Use today's date
        $invoiceData['invoiceDate'] = date('Y-m-d');
        
        echo "   📊 Rate: {$invoiceData['items'][0]['rate']} | ";
        echo "Buyer: {$invoiceData['buyerRegistrationType']} | ";
        echo "Sales: Rs.{$invoiceData['items'][0]['valueSalesExcludingST']}\n";
        
        // Test with validation endpoint first (faster)
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://gw.fbr.gov.pk/di_data/v1/di/validateinvoicedata_sb',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($invoiceData),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $bearerToken
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $totalTime = curl_getinfo($curl, CURLINFO_TOTAL_TIME);
        curl_close($curl);

        if ($httpCode === 200 && $response) {
            $responseData = json_decode($response, true);
            
            if ($responseData && isset($responseData['validationResponse'])) {
                $validation = $responseData['validationResponse'];
                
                if ($validation['statusCode'] === '00' && $validation['status'] === 'Valid') {
                    echo "   ✅ VALIDATION: SUCCESS ({$totalTime}s)\n";
                    
                    // Try actual submission for key scenarios
                    if (in_array($scenarioId, ['SN001', 'SN002', 'SN005', 'SN010', 'SN020', 'SN026'])) {
                        echo "   🚀 Attempting submission...\n";
                        
                        $curl = curl_init();
                        curl_setopt_array($curl, [
                            CURLOPT_URL => 'https://gw.fbr.gov.pk/di_data/v1/di/postinvoicedata_sb',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => json_encode($invoiceData),
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_SSL_VERIFYHOST => false,
                            CURLOPT_HTTPHEADER => [
                                'Content-Type: application/json',
                                'Authorization: Bearer ' . $bearerToken
                            ],
                        ]);

                        $submitResponse = curl_exec($curl);
                        $submitHttpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                        curl_close($curl);

                        if ($submitHttpCode === 200 && $submitResponse) {
                            $submitData = json_decode($submitResponse, true);
                            if ($submitData && isset($submitData['invoiceNumber'])) {
                                echo "   🎉 SUBMISSION: SUCCESS\n";
                                echo "   📋 FBR Invoice: {$submitData['invoiceNumber']}\n";
                                $results[$scenarioId] = [
                                    'status' => 'success',
                                    'validation' => 'valid',
                                    'submission' => 'success',
                                    'invoice_number' => $submitData['invoiceNumber']
                                ];
                            } else {
                                echo "   ⚠️  SUBMISSION: No invoice number returned\n";
                                $results[$scenarioId] = [
                                    'status' => 'partial',
                                    'validation' => 'valid',
                                    'submission' => 'failed'
                                ];
                            }
                        } else {
                            echo "   ⚠️  SUBMISSION: HTTP $submitHttpCode\n";
                            $results[$scenarioId] = [
                                'status' => 'partial',
                                'validation' => 'valid',
                                'submission' => 'failed'
                            ];
                        }
                        
                        sleep(2); // Rate limiting between submissions
                    } else {
                        $results[$scenarioId] = [
                            'status' => 'success',
                            'validation' => 'valid',
                            'submission' => 'skipped'
                        ];
                    }
                    
                    $successCount++;
                } else {
                    echo "   ❌ VALIDATION: {$validation['status']} - {$validation['error']}\n";
                    $results[$scenarioId] = [
                        'status' => 'failed',
                        'validation' => 'invalid',
                        'error' => $validation['error']
                    ];
                }
            } else {
                echo "   ❌ VALIDATION: Invalid response format\n";
                $results[$scenarioId] = [
                    'status' => 'failed',
                    'validation' => 'error',
                    'error' => 'Invalid response format'
                ];
            }
        } else {
            echo "   ❌ VALIDATION: HTTP $httpCode" . ($response ? '' : ' (empty response)') . "\n";
            $results[$scenarioId] = [
                'status' => 'failed',
                'validation' => 'error',
                'error' => "HTTP $httpCode"
            ];
        }
        
    } catch (Exception $e) {
        echo "   ❌ ERROR: {$e->getMessage()}\n";
        $results[$scenarioId] = [
            'status' => 'failed',
            'validation' => 'error',
            'error' => $e->getMessage()
        ];
    }
    
    echo "\n";
    
    // Rate limiting between tests
    sleep(1);
}

// Summary Report
echo str_repeat("=", 60) . "\n";
echo "📊 COMPREHENSIVE TEST RESULTS\n";
echo str_repeat("=", 60) . "\n";

echo "✅ Valid Scenarios: $successCount/$totalCount (" . round(($successCount/$totalCount)*100) . "%)\n";
echo "🔑 Working NTN: $workingNTN\n";
echo "📅 Test Date: " . date('Y-m-d H:i:s') . "\n\n";

// Categorize results
$categories = [
    'Manufacturing & Standard Sales' => ['SN001', 'SN002', 'SN003', 'SN004', 'SN005', 'SN006', 'SN007', 'SN008', 'SN009'],
    'Services & Specialized Industries' => ['SN010', 'SN011', 'SN012', 'SN013', 'SN014', 'SN015', 'SN016', 'SN017', 'SN018', 'SN019'],
    'Specialized Products & Retail' => ['SN020', 'SN021', 'SN022', 'SN023', 'SN024', 'SN025', 'SN026', 'SN027', 'SN028']
];

foreach ($categories as $category => $scenarios) {
    echo "📋 $category:\n";
    foreach ($scenarios as $scenarioId) {
        $result = $results[$scenarioId] ?? ['status' => 'not_tested'];
        $icon = match($result['status']) {
            'success' => '✅',
            'partial' => '⚠️',
            'failed' => '❌',
            default => '❓'
        };
        
        $scenarioName = $allScenarios[$scenarioId]['name'] ?? 'Unknown';
        echo "   $icon $scenarioId: $scenarioName\n";
        
        if (isset($result['invoice_number'])) {
            echo "     📋 Invoice: {$result['invoice_number']}\n";
        }
        if (isset($result['error'])) {
            echo "     ❌ Error: {$result['error']}\n";
        }
    }
    echo "\n";
}

// Final Summary
echo "🎯 FINAL SUMMARY:\n";
if ($successCount >= 20) {
    echo "🎉 EXCELLENT! Most scenarios are working!\n";
    echo "📦 Your FBR Laravel package is production-ready!\n";
} elseif ($successCount >= 10) {
    echo "✅ GOOD! Many scenarios are working!\n";
    echo "📦 Package is functional with some scenarios needing attention!\n";
} else {
    echo "⚠️  LIMITED SUCCESS. Check specific errors above.\n";
}

echo "\n💡 NEXT STEPS:\n";
echo "1. Use NTN $workingNTN in your Laravel application\n";
echo "2. Focus on scenarios that validated successfully\n";
echo "3. Update .env: FBR_BEARER_TOKEN=$bearerToken\n";
echo "4. Set FBR_SANDBOX=true for testing\n";
echo "5. Run Laravel migrations and test integration\n";

echo "\n✨ FBR Digital Invoicing package testing complete!\n";

?>