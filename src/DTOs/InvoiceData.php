<?php

namespace Fbr\DigitalInvoicing\DTOs;

class InvoiceData
{
    public function __construct(
        public string $invoiceType,
        public string $invoiceDate,
        public string $sellerNTNCNIC,
        public string $sellerBusinessName,
        public string $sellerProvince,
        public string $sellerAddress,
        public string $buyerNTNCNIC,
        public string $buyerBusinessName,
        public string $buyerProvince,
        public string $buyerAddress,
        public string $buyerRegistrationType,
        public string $invoiceRefNo = '',
        public ?string $scenarioId = null,
        public array $items = []
    ) {}

    public function toArray(): array
    {
        $data = [
            'invoiceType' => $this->invoiceType,
            'invoiceDate' => $this->invoiceDate,
            'sellerNTNCNIC' => $this->sellerNTNCNIC,
            'sellerBusinessName' => $this->sellerBusinessName,
            'sellerProvince' => $this->sellerProvince,
            'sellerAddress' => $this->sellerAddress,
            'buyerNTNCNIC' => $this->buyerNTNCNIC,
            'buyerBusinessName' => $this->buyerBusinessName,
            'buyerProvince' => $this->buyerProvince,
            'buyerAddress' => $this->buyerAddress,
            'buyerRegistrationType' => $this->buyerRegistrationType,
            'invoiceRefNo' => $this->invoiceRefNo,
            'items' => array_map(fn($item) => $item->toArray(), $this->items)
        ];

        // Add scenarioId only for sandbox
        if ($this->scenarioId && config('fbr-digital-invoicing.sandbox', true)) {
            $data['scenarioId'] = $this->scenarioId;
        }

        return $data;
    }

    public function addItem(InvoiceItemData $item): self
    {
        $this->items[] = $item;
        return $this;
    }
}