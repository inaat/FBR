<?php

namespace Fbr\DigitalInvoicing\DTOs;

class InvoiceResponse
{
    public function __construct(
        public ?string $invoiceNumber = null,
        public ?string $dated = null,
        public ?ValidationResponse $validationResponse = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            invoiceNumber: $data['invoiceNumber'] ?? null,
            dated: $data['dated'] ?? null,
            validationResponse: isset($data['validationResponse']) 
                ? ValidationResponse::fromArray($data['validationResponse'])
                : null
        );
    }

    public function isValid(): bool
    {
        return $this->validationResponse?->statusCode === '00';
    }

    public function getErrors(): array
    {
        if (!$this->validationResponse) return [];
        
        $errors = [];
        
        if ($this->validationResponse->error) {
            $errors[] = $this->validationResponse->error;
        }

        if ($this->validationResponse->invoiceStatuses) {
            foreach ($this->validationResponse->invoiceStatuses as $status) {
                if ($status->error) {
                    $errors[] = $status->error;
                }
            }
        }

        return $errors;
    }
}

class ValidationResponse
{
    public function __construct(
        public string $statusCode,
        public string $status,
        public ?string $errorCode = null,
        public ?string $error = null,
        public ?array $invoiceStatuses = null
    ) {}

    public static function fromArray(array $data): self
    {
        $invoiceStatuses = null;
        if (isset($data['invoiceStatuses']) && is_array($data['invoiceStatuses'])) {
            $invoiceStatuses = array_map(
                fn($status) => InvoiceStatus::fromArray($status),
                $data['invoiceStatuses']
            );
        }

        return new self(
            statusCode: $data['statusCode'],
            status: $data['status'],
            errorCode: $data['errorCode'] ?? null,
            error: $data['error'] ?? null,
            invoiceStatuses: $invoiceStatuses
        );
    }
}

class InvoiceStatus
{
    public function __construct(
        public string $itemSNo,
        public string $statusCode,
        public string $status,
        public ?string $invoiceNo = null,
        public ?string $errorCode = null,
        public ?string $error = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            itemSNo: $data['itemSNo'],
            statusCode: $data['statusCode'],
            status: $data['status'],
            invoiceNo: $data['invoiceNo'] ?? null,
            errorCode: $data['errorCode'] ?? null,
            error: $data['error'] ?? null
        );
    }
}