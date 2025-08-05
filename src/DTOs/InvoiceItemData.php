<?php

namespace Fbr\DigitalInvoicing\DTOs;

class InvoiceItemData
{
    public function __construct(
        public string $hsCode,
        public string $productDescription,
        public string $rate,
        public string $uoM,
        public float $quantity,
        public float $totalValues,
        public float $valueSalesExcludingST,
        public float $fixedNotifiedValueOrRetailPrice,
        public float $salesTaxApplicable,
        public float $salesTaxWithheldAtSource,
        public float $extraTax = 0.0,
        public float $furtherTax = 0.0,
        public string $sroScheduleNo = '',
        public float $fedPayable = 0.0,
        public float $discount = 0.0,
        public string $saleType = 'Goods at standard rate (default)',
        public string $sroItemSerialNo = ''
    ) {}

    public function toArray(): array
    {
        return [
            'hsCode' => $this->hsCode,
            'productDescription' => $this->productDescription,
            'rate' => $this->rate,
            'uoM' => $this->uoM,
            'quantity' => $this->quantity,
            'totalValues' => $this->totalValues,
            'valueSalesExcludingST' => $this->valueSalesExcludingST,
            'fixedNotifiedValueOrRetailPrice' => $this->fixedNotifiedValueOrRetailPrice,
            'salesTaxApplicable' => $this->salesTaxApplicable,
            'salesTaxWithheldAtSource' => $this->salesTaxWithheldAtSource,
            'extraTax' => $this->extraTax,
            'furtherTax' => $this->furtherTax,
            'sroScheduleNo' => $this->sroScheduleNo,
            'fedPayable' => $this->fedPayable,
            'discount' => $this->discount,
            'saleType' => $this->saleType,
            'sroItemSerialNo' => $this->sroItemSerialNo,
        ];
    }
}