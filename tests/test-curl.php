<?php
/**
 * Direct cURL Test for FBR Digital Invoicing API
 * This replicates the exact cURL request you provided
 */

// Test data (SN001 scenario - Standard Rate to Registered Buyers)
$data = [
    'invoiceType' => 'Sale Invoice',
    'invoiceDate' => '2025-08-04',
    'sellerNTNCNIC' => '5076033',
    'sellerBusinessName' => 'Test Company',
    'sellerProvince' => 'Sindh',
    'sellerAddress' => 'Karachi',
    'buyerNTNCNIC' => '2046004',
    'buyerBusinessName' => 'Test Customer Ltd',
    'buyerProvince' => 'Sindh',
    'buyerAddress' => 'Karachi',
    'buyerRegistrationType' => 'Registered',
    'invoiceRefNo' => '',
    'scenarioId' => 'SN001',
    'items' => [
        [
            'hsCode' => '0101.2100',
            'productDescription' => 'Test Product',
            'rate' => '18%',
            'uoM' => 'Numbers, pieces, units',
            'quantity' => 1.0,
            'totalValues' => 0.0,
            'valueSalesExcludingST' => 1000.0,
            'fixedNotifiedValueOrRetailPrice' => 0.0,
            'salesTaxApplicable' => 180.0,
            'salesTaxWithheldAtSource' => 0.0,
            'extraTax' => 0.0,
            'furtherTax' => 0.0,
            'sroScheduleNo' => '',
            'fedPayable' => 0.0,
            'discount' => 0.0,
            'saleType' => 'Goods at standard rate (default)',
            'sroItemSerialNo' => ''
        ]
    ]
];

echo "🚀 FBR Digital Invoicing API Test\n";
echo "================================\n\n";

echo "📋 Invoice Data:\n";
echo "- Invoice Type: " . $data['invoiceType'] . "\n";
echo "- Invoice Date: " . $data['invoiceDate'] . "\n";
echo "- Seller NTN: " . $data['sellerNTNCNIC'] . "\n";
echo "- Buyer NTN: " . $data['buyerNTNCNIC'] . "\n";
echo "- Scenario: " . $data['scenarioId'] . "\n";
echo "- Items: " . count($data['items']) . "\n";
echo "- Sales Value: Rs. " . number_format($data['items'][0]['valueSalesExcludingST'], 2) . "\n";
echo "- Sales Tax: Rs. " . number_format($data['items'][0]['salesTaxApplicable'], 2) . "\n\n";

// Initialize cURL - exactly as you provided
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://gw.fbr.gov.pk/di_data/v1/di/postinvoicedata_sb',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Bearer d5166958-c131-3bf0-b0e0-ff0ccff78af8'
    ),
));

echo "📡 Making API Request...\n";
echo "🌐 URL: https://gw.fbr.gov.pk/di_data/v1/di/postinvoicedata_sb\n";
echo "🔑 Authorization: Bearer d5166958-c131-3bf0-b0e0-ff0ccff78af8\n";
echo "📊 Method: POST\n";
echo "📄 Content-Type: application/json\n\n";

// Execute the request
$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$error = curl_error($curl);

curl_close($curl);

echo "📡 Response Details:\n";
echo "- HTTP Status Code: " . $httpCode . "\n";

if ($error) {
    echo "❌ cURL Error: " . $error . "\n";
    exit(1);
}

if ($httpCode === 200) {
    echo "✅ Request Successful!\n\n";
    
    $responseData = json_decode($response, true);
    
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "📄 Parsed Response:\n";
        
        // Check if invoice was created successfully
        if (isset($responseData['invoiceNumber'])) {
            echo "✅ Invoice Number: " . $responseData['invoiceNumber'] . "\n";
        }
        
        if (isset($responseData['dated'])) {
            echo "📅 Date: " . $responseData['dated'] . "\n";
        }
        
        // Check validation response
        if (isset($responseData['validationResponse'])) {
            $validation = $responseData['validationResponse'];
            
            echo "📊 Validation Status: " . ($validation['status'] ?? 'Unknown') . "\n";
            echo "🔢 Status Code: " . ($validation['statusCode'] ?? 'Unknown') . "\n";
            
            if (!empty($validation['error'])) {
                echo "❌ Error: " . $validation['error'] . "\n";
            }
            
            // Check item statuses
            if (isset($validation['invoiceStatuses']) && is_array($validation['invoiceStatuses'])) {
                echo "\n📋 Item Statuses:\n";
                foreach ($validation['invoiceStatuses'] as $itemStatus) {
                    echo "- Item " . ($itemStatus['itemSNo'] ?? '?') . ": " . 
                         ($itemStatus['status'] ?? 'Unknown');
                    
                    if (isset($itemStatus['invoiceNo'])) {
                        echo " (Invoice: " . $itemStatus['invoiceNo'] . ")";
                    }
                    
                    if (!empty($itemStatus['error'])) {
                        echo " - Error: " . $itemStatus['error'];
                    }
                    
                    echo "\n";
                }
            }
            
            // Overall status
            if (($validation['statusCode'] ?? '') === '00') {
                echo "\n🎉 SUCCESS: Invoice submitted and validated successfully!\n";
            } else {
                echo "\n⚠️  WARNING: Invoice validation issues detected.\n";
            }
        }
        
        echo "\n📄 Full JSON Response:\n";
        echo json_encode($responseData, JSON_PRETTY_PRINT) . "\n";
        
    } else {
        echo "❌ JSON Parse Error: " . json_last_error_msg() . "\n";
        echo "📄 Raw Response:\n" . $response . "\n";
    }
    
} else {
    echo "❌ Request Failed!\n";
    echo "📄 Response Body:\n" . $response . "\n";
    
    // Try to parse error response
    $errorData = json_decode($response, true);
    if (json_last_error() === JSON_ERROR_NONE && isset($errorData['validationResponse'])) {
        echo "\n📊 Error Details:\n";
        if (isset($errorData['validationResponse']['error'])) {
            echo "- " . $errorData['validationResponse']['error'] . "\n";
        }
        
        if (isset($errorData['validationResponse']['invoiceStatuses'])) {
            foreach ($errorData['validationResponse']['invoiceStatuses'] as $status) {
                if (!empty($status['error'])) {
                    echo "- Item " . ($status['itemSNo'] ?? '?') . ": " . $status['error'] . "\n";
                }
            }
        }
    }
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "📊 Test Summary:\n";
echo "- API Endpoint: Sandbox (postinvoicedata_sb)\n";
echo "- HTTP Status: " . $httpCode . "\n";
echo "- Success: " . ($httpCode === 200 ? "✅ Yes" : "❌ No") . "\n";

if ($httpCode === 200) {
    $responseData = json_decode($response, true);
    if (isset($responseData['validationResponse']['statusCode'])) {
        $statusCode = $responseData['validationResponse']['statusCode'];
        echo "- Validation: " . ($statusCode === '00' ? "✅ Valid" : "❌ Invalid") . "\n";
        
        if (isset($responseData['invoiceNumber'])) {
            echo "- FBR Invoice: " . $responseData['invoiceNumber'] . "\n";
        }
    }
}

echo "\n💡 Next Steps:\n";
echo "1. If successful, try the validation endpoint\n";
echo "2. Test different scenarios (SN002, SN005, etc.)\n";
echo "3. Integrate with the Laravel package\n";
echo "4. Set up production environment\n";

// Test validation endpoint as well
echo "\n🔍 Testing Validation Endpoint...\n";

$validationCurl = curl_init();
curl_setopt_array($validationCurl, array(
    CURLOPT_URL => 'https://gw.fbr.gov.pk/di_data/v1/di/validateinvoicedata_sb',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Bearer d5166958-c131-3bf0-b0e0-ff0ccff78af8'
    ),
));

$validationResponse = curl_exec($validationCurl);
$validationHttpCode = curl_getinfo($validationCurl, CURLINFO_HTTP_CODE);
curl_close($validationCurl);

echo "📊 Validation Response Code: " . $validationHttpCode . "\n";

if ($validationHttpCode === 200) {
    $validationData = json_decode($validationResponse, true);
    if ($validationData && isset($validationData['validationResponse'])) {
        echo "✅ Validation Status: " . ($validationData['validationResponse']['status'] ?? 'Unknown') . "\n";
        echo "🔢 Validation Code: " . ($validationData['validationResponse']['statusCode'] ?? 'Unknown') . "\n";
    }
} else {
    echo "❌ Validation failed with code: " . $validationHttpCode . "\n";
}

echo "\n🎯 Test Complete!\n";
?>