<?php

namespace Fbr\DigitalInvoicing\Services;

use Fbr\DigitalInvoicing\DTOs\InvoiceData;
use Fbr\DigitalInvoicing\DTOs\InvoiceResponse;

class FbrService
{
    private FbrDigitalInvoicingService $invoicingService;
    private FbrReferenceService $referenceService;

    public function __construct(
        FbrDigitalInvoicingService $invoicingService,
        FbrReferenceService $referenceService
    ) {
        $this->invoicingService = $invoicingService;
        $this->referenceService = $referenceService;
    }

    // Invoice Operations
    public function postInvoiceData(InvoiceData $invoiceData): InvoiceResponse
    {
        return $this->invoicingService->postInvoiceData($invoiceData);
    }

    public function validateInvoiceData(InvoiceData $invoiceData): InvoiceResponse
    {
        return $this->invoicingService->validateInvoiceData($invoiceData);
    }

    // Reference Data Operations
    public function getProvinces(): array
    {
        return $this->referenceService->getProvinces();
    }

    public function getDocumentTypeCodes(): array
    {
        return $this->referenceService->getDocumentTypeCodes();
    }

    public function getItemDescCodes(): array
    {
        return $this->referenceService->getItemDescCodes();
    }

    public function getSroItemCodes(): array
    {
        return $this->referenceService->getSroItemCodes();
    }

    public function getTransactionTypeCodes(): array
    {
        return $this->referenceService->getTransactionTypeCodes();
    }

    public function getUomCodes(): array
    {
        return $this->referenceService->getUomCodes();
    }

    public function getSroSchedule(int $rateId, string $date, int $originationSupplierCsv = 1): array
    {
        return $this->referenceService->getSroSchedule($rateId, $date, $originationSupplierCsv);
    }

    public function getSaleTypeToRate(string $date, int $transTypeId, int $originationSupplier): array
    {
        return $this->referenceService->getSaleTypeToRate($date, $transTypeId, $originationSupplier);
    }

    public function getHsUom(string $hsCode, int $annexureId): array
    {
        return $this->referenceService->getHsUom($hsCode, $annexureId);
    }

    public function getSroItems(string $date, int $sroId): array
    {
        return $this->referenceService->getSroItems($date, $sroId);
    }

    public function checkStatl(string $regno, string $date): array
    {
        return $this->referenceService->checkStatl($regno, $date);
    }

    public function getRegistrationType(string $registrationNo): array
    {
        return $this->referenceService->getRegistrationType($registrationNo);
    }
}