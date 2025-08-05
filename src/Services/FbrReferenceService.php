<?php
namespace Fbr\DigitalInvoicing\Services;

use Fbr\DigitalInvoicing\Exceptions\FbrDigitalInvoicingException;

class FbrReferenceService
{
    private string $baseUrl;
    private string $bearerToken;
    private int $timeout;

    public function __construct($baseUrl = null, $bearerToken = null, $timeout = 30)
    {
        $this->baseUrl = $baseUrl ?: (function_exists('config') ? config('fbr-digital-invoicing.urls.reference') : 'https://gw.fbr.gov.pk/pdi/v1/');
        $this->bearerToken = $bearerToken ?: (function_exists('config') ? config('fbr-digital-invoicing.bearer_token') : '');
        $this->timeout = $timeout ?: (function_exists('config') ? config('fbr-digital-invoicing.timeout', 30) : 30);
    }

    /**
     * Get provinces
     */
    public function getProvinces(): array
    {
        return $this->makeRequest('GET', 'provinces');
    }

    /**
     * Get document type codes
     */
    public function getDocumentTypeCodes(): array
    {
        return $this->makeRequest('GET', 'doctypecode');
    }

    /**
     * Get item description codes
     */
    public function getItemDescCodes(): array
    {
        return $this->makeRequest('GET', 'itemdesccode');
    }

    /**
     * Get SRO item codes
     */
    public function getSroItemCodes(): array
    {
        return $this->makeRequest('GET', 'sroitemcode');
    }

    /**
     * Get transaction type codes
     */
    public function getTransactionTypeCodes(): array
    {
        return $this->makeRequest('GET', 'transtypecode');
    }

    /**
     * Get UOM (Unit of Measurement) codes
     */
    public function getUomCodes(): array
    {
        return $this->makeRequest('GET', 'uom');
    }

    /**
     * Get SRO schedule
     */
    public function getSroSchedule(int $rateId, string $date, int $originationSupplierCsv = 1): array
    {
        $params = [
            'rate_id' => $rateId,
            'date' => $date,
            'origination_supplier_csv' => $originationSupplierCsv
        ];
        
        return $this->makeRequest('GET', 'SroSchedule', $params);
    }

    /**
     * Get sale type to rate mapping
     */
    public function getSaleTypeToRate(string $date, int $transTypeId, int $originationSupplier): array
    {
        $params = [
            'date' => $date,
            'transTypeId' => $transTypeId,
            'originationSupplier' => $originationSupplier
        ];
        
        return $this->makeRequest('GET', 'v2/SaleTypeToRate', $params);
    }

    /**
     * Get HS code with UOM
     */
    public function getHsUom(string $hsCode, int $annexureId): array
    {
        $params = [
            'hs_code' => $hsCode,
            'annexure_id' => $annexureId
        ];
        
        return $this->makeRequest('GET', 'v2/HS_UOM', $params);
    }

    /**
     * Get SRO items
     */
    public function getSroItems(string $date, int $sroId): array
    {
        $params = [
            'date' => $date,
            'sro_id' => $sroId
        ];
        
        return $this->makeRequest('GET', 'v2/SROItem', $params);
    }

    /**
     * Check STATL status
     */
    public function checkStatl(string $regno, string $date): array
    {
        $data = [
            'regno' => $regno,
            'date' => $date
        ];
        
        return $this->makeRequest('POST', '../dist/v1/statl', $data);
    }

    /**
     * Get registration type
     */
    public function getRegistrationType(string $registrationNo): array
    {
        $data = [
            'Registration_No' => $registrationNo
        ];
        
        return $this->makeRequest('POST', '../dist/v1/Get_Reg_Type', $data);
    }

    /**
     * Make HTTP request
     */
    private function makeRequest(string $method, string $endpoint, array $data = []): array
    {
        $url = $this->baseUrl . $endpoint;
        if ($method === 'GET' && !empty($data)) {
            $url .= '?' . http_build_query($data);
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->bearerToken,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
        ]);

        if ($method === 'POST') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode >= 200 && $httpCode < 300 && $response) {
            $decoded = json_decode($response, true);
            if ($decoded !== null) {
                return $decoded;
            }
        }

        throw new FbrDigitalInvoicingException(
            'FBR Reference API Error: HTTP ' . $httpCode . ' - ' . ($response ?: 'No response'),
            $httpCode
        );
    }
}