<?php
/**
 * FBR API Diagnostic Tool
 * Helps diagnose authentication and authorization issues
 */

echo "🔍 FBR API Diagnostic Tool\n";
echo "=========================\n\n";

// Test different scenarios to identify the issue
$bearerToken = 'd5166958-c131-3bf0-b0e0-ff0ccff78af8';

// Test 1: Check if the bearer token format is correct
echo "1️⃣  Bearer Token Analysis\n";
echo "------------------------\n";
echo "Token: $bearerToken\n";
echo "Length: " . strlen($bearerToken) . " characters\n";
echo "Format: " . (preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/', $bearerToken) ? "✅ Valid UUID format" : "❌ Invalid format") . "\n\n";

// Test 2: Try with different NTN formats
echo "2️⃣  Testing Different NTN Formats\n";
echo "--------------------------------\n";

$testNTNs = [
    '8885801' => '7-digit NTN',
    '0008885801' => '10-digit padded NTN', 
    '0000008885801' => '13-digit CNIC format',
    '2046004' => '7-digit buyer NTN',
    '0002046004' => '10-digit padded buyer NTN',
    '1000000000000' => '13-digit test CNIC'
];

foreach ($testNTNs as $ntn => $description) {
    echo "Testing $description ($ntn):\n";
    
    $testData = [
        'invoiceType' => 'Sale Invoice',
        'invoiceDate' => '2025-08-04',
        'sellerNTNCNIC' => $ntn,
        'sellerBusinessName' => 'Test Company',
        'sellerProvince' => 'Sindh',
        'sellerAddress' => 'Karachi',
        'buyerNTNCNIC' => '2046004',
        'buyerBusinessName' => 'Test Customer',
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

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://gw.fbr.gov.pk/di_data/v1/di/validateinvoicedata_sb',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($testData),
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $bearerToken
        ),
    ));

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    echo "  Status: $httpCode";
    
    if ($httpCode === 200) {
        echo " ✅ SUCCESS!\n";
        $data = json_decode($response, true);
        if (isset($data['validationResponse']['status'])) {
            echo "  Validation: " . $data['validationResponse']['status'] . "\n";
        }
        echo "  🎉 This NTN format works!\n\n";
        break; // Found working format
    } else if ($httpCode === 401) {
        echo " ❌ Unauthorized\n";
        $data = json_decode($response, true);
        if (isset($data['validationResponse']['error'])) {
            echo "  Error: " . $data['validationResponse']['error'] . "\n";
        }
    } else {
        echo " ❌ HTTP $httpCode\n";
    }
    echo "\n";
}

// Test 3: Check reference APIs (these might work even if invoice APIs don't)
echo "3️⃣  Testing Reference APIs (No NTN Required)\n";
echo "-------------------------------------------\n";

$referenceAPIs = [
    'provinces' => 'https://gw.fbr.gov.pk/pdi/v1/provinces',
    'doctypes' => 'https://gw.fbr.gov.pk/pdi/v1/doctypecode',
    'uom' => 'https://gw.fbr.gov.pk/pdi/v1/uom'
];

foreach ($referenceAPIs as $name => $url) {
    echo "Testing $name API... ";
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $bearerToken
        ),
    ));

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($httpCode === 200) {
        $data = json_decode($response, true);
        echo "✅ SUCCESS (" . count($data) . " items)\n";
    } else {
        echo "❌ Failed ($httpCode)\n";
    }
}

// Test 4: Try STATL API
echo "\n4️⃣  Testing STATL API\n";
echo "-------------------\n";

$statlData = ['regno' => '8885801', 'date' => '2025-08-04'];

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://gw.fbr.gov.pk/dist/v1/statl',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($statlData),
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $bearerToken
    ),
));

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

echo "STATL API Status: $httpCode";
if ($httpCode === 200) {
    echo " ✅ SUCCESS\n";
    $data = json_decode($response, true);
    echo "Registration Status: " . ($data['status'] ?? 'Unknown') . "\n";
} else {
    echo " ❌ Failed\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "🔍 DIAGNOSTIC SUMMARY\n";
echo str_repeat("=", 50) . "\n";

echo "\n❌ Issue Identified: Authentication/Authorization Error\n\n";

echo "🔧 POSSIBLE SOLUTIONS:\n\n";

echo "1️⃣  BEARER TOKEN ISSUES:\n";
echo "   - Token may be expired (5-year validity)\n";
echo "   - Token may be invalid or not activated\n";
echo "   - Contact FBR to verify token status\n\n";

echo "2️⃣  NTN/CNIC FORMAT ISSUES:\n";
echo "   - FBR expects specific NTN format (7 or 9 digits)\n";
echo "   - CNIC format should be 13 digits\n";
echo "   - Try different format variations above\n\n";

echo "3️⃣  REGISTRATION ISSUES:\n";
echo "   - Seller NTN must be registered with FBR\n";
echo "   - NTN must be authorized for this bearer token\n";
echo "   - Check if seller is active in sales tax\n\n";

echo "4️⃣  ENVIRONMENT ISSUES:\n";
echo "   - Ensure using sandbox environment\n";
echo "   - Check network connectivity\n";
echo "   - Verify SSL certificates\n\n";

echo "📞 NEXT STEPS:\n";
echo "1. Contact FBR support to verify bearer token\n";
echo "2. Confirm your NTN registration status\n";
echo "3. Request token authorization for your NTN\n";
echo "4. Try with a different test NTN if available\n";
echo "5. Verify sandbox vs production environment\n\n";

echo "📧 FBR Support Contacts:\n";
echo "- Technical Support: [Contact from FBR documentation]\n";
echo "- Digital Invoicing Help: [Contact from FBR documentation]\n";

echo "\n⚠️  NOTE: The error message suggests the seller NTN '8885801' is not\n";
echo "authorized for the provided bearer token. This is a common issue\n";
echo "during initial setup.\n";

?>