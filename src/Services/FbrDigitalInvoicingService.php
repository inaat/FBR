<?php

namespace Fbr\DigitalInvoicing\Services;

use Fbr\DigitalInvoicing\DTOs\InvoiceData;
use Fbr\DigitalInvoicing\DTOs\InvoiceResponse;
use Fbr\DigitalInvoicing\Exceptions\FbrDigitalInvoicingException;

class FbrDigitalInvoicingService
{
    private string $baseUrl;
    private string $bearerToken;
    private bool $isSandbox;
    private int $timeout;
    private int $retryAttempts;
    private bool $loggingEnabled;

    public function __construct($bearerToken = null, $isSandbox = true, $timeout = 30, $retryAttempts = 3, $loggingEnabled = false)
    {
        $this->bearerToken = $bearerToken ?: (function_exists('config') ? config('fbr-digital-invoicing.bearer_token') : '');
        $this->isSandbox = $isSandbox !== null ? $isSandbox : (function_exists('config') ? config('fbr-digital-invoicing.sandbox', true) : true);
        $this->timeout = $timeout ?: (function_exists('config') ? config('fbr-digital-invoicing.timeout', 30) : 30);
        $this->retryAttempts = $retryAttempts ?: (function_exists('config') ? config('fbr-digital-invoicing.retry_attempts', 3) : 3);
        $this->loggingEnabled = $loggingEnabled !== null ? $loggingEnabled : (function_exists('config') ? config('fbr-digital-invoicing.logging.enabled', true) : false);
        
        $this->baseUrl = $this->isSandbox 
            ? (function_exists('config') ? config('fbr-digital-invoicing.urls.sandbox') : 'https://gw.fbr.gov.pk/di_data/v1/di/')
            : (function_exists('config') ? config('fbr-digital-invoicing.urls.production') : 'https://gw.fbr.gov.pk/di_data/v1/di/');

        if (empty($this->bearerToken)) {
            throw new FbrDigitalInvoicingException('FBR Bearer token is not configured');
        }
    }

    /**
     * Post Invoice Data to FBR
     */
    public function postInvoiceData(InvoiceData $invoiceData): InvoiceResponse
    {
        $endpoint = $this->isSandbox ? 'postinvoicedata_sb' : 'postinvoicedata';
        $data = $invoiceData->toArray();
        
        if ($this->loggingEnabled) {
            error_log('FBR: Posting invoice data - ' . $invoiceData->invoiceType . ' from ' . $invoiceData->sellerNTNCNIC . ' to ' . $invoiceData->buyerNTNCNIC);
        }

        $response = $this->makeRequest('POST', $endpoint, $data);
        return InvoiceResponse::fromArray($response);
    }

    /**
     * Validate Invoice Data with FBR
     */
    public function validateInvoiceData(InvoiceData $invoiceData): InvoiceResponse
    {
        $endpoint = $this->isSandbox ? 'validateinvoicedata_sb' : 'validateinvoicedata';
        $data = $invoiceData->toArray();
        
        if ($this->loggingEnabled) {
            error_log('FBR: Validating invoice data - ' . $invoiceData->invoiceType . ' from ' . $invoiceData->sellerNTNCNIC . ' to ' . $invoiceData->buyerNTNCNIC);
        }

        $response = $this->makeRequest('POST', $endpoint, $data);
        return InvoiceResponse::fromArray($response);
    }

    /**
     * Make HTTP request to FBR API with retry logic
     */
    private function makeRequest(string $method, string $endpoint, array $data = []): array
    {
        $lastException = null;
        
        for ($attempt = 1; $attempt <= $this->retryAttempts; $attempt++) {
            try {
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => $this->baseUrl . $endpoint,
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
                    if ($this->loggingEnabled && $attempt > 1) {
                        error_log("FBR API request succeeded on attempt {$attempt}");
                    }
                    $decoded = json_decode($response, true);
                    if ($decoded !== null) {
                        return $decoded;
                    }
                }

                // Handle specific HTTP status codes
                if ($httpCode === 401) {
                    throw new FbrDigitalInvoicingException(
                        'Unauthorized: Invalid or expired bearer token',
                        401
                    );
                }

                if ($httpCode >= 500) {
                    // Server errors - retry
                    $lastException = new FbrDigitalInvoicingException(
                        'FBR API Server Error: HTTP ' . $httpCode . ' - ' . ($response ?: 'No response'),
                        $httpCode
                    );
                    
                    if ($this->loggingEnabled) {
                        error_log("FBR API server error on attempt {$attempt}: HTTP {$httpCode}");
                    }
                    
                    if ($attempt < $this->retryAttempts) {
                        sleep($attempt); // Exponential backoff
                        continue;
                    }
                } else {
                    // Client errors - don't retry
                    throw new FbrDigitalInvoicingException(
                        'FBR API Client Error: HTTP ' . $httpCode . ' - ' . ($response ?: 'No response'),
                        $httpCode
                    );
                }
            } catch (FbrDigitalInvoicingException $e) {
                if ($e->getCode() === 401 || ($e->getCode() >= 400 && $e->getCode() < 500)) {
                    // Don't retry client errors
                    throw $e;
                }
                $lastException = $e;
                
                if ($this->loggingEnabled) {
                    error_log("FBR API request failed on attempt {$attempt}: " . $e->getMessage());
                }
                
                if ($attempt < $this->retryAttempts) {
                    sleep($attempt);
                    continue;
                }
            }
        }

        throw $lastException ?: new FbrDigitalInvoicingException('Unknown error occurred');
    }
}
