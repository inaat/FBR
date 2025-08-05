<?php

namespace Fbr\DigitalInvoicing\Builders;

use Fbr\DigitalInvoicing\DTOs\InvoiceItemData;
use Fbr\DigitalInvoicing\Exceptions\FbrDigitalInvoicingException;

class InvoiceItemBuilder
{
    private ?string $hsCode = null;
    private ?string $productDescription = null;
    private ?string $rate = null;
    private ?string $uoM = null;
    private ?float $quantity = null;
    private float $totalValues = 0.0;
    private ?float $valueSalesExcludingST = null;
    private float $fixedNotifiedValueOrRetailPrice = 0.0;
    private ?float $salesTaxApplicable = null;
    private float $salesTaxWithheldAtSource = 0.0;
    private float $extraTax = 0.0;
    private float $furtherTax = 0.0;
    private string $sroScheduleNo = '';
    private float $fedPayable = 0.0;
    private float $discount = 0.0;
    private string $saleType = 'Goods at standard rate (default)';
    private string $sroItemSerialNo = '';

    public function setHsCode(string $hsCode): self
    {
        $this->hsCode = $hsCode;
        return $this;
    }

    public function setProductDescription(string $productDescription): self
    {
        $this->productDescription = $productDescription;
        return $this;
    }

    public function setRate(string $rate): self
    {
        $this->rate = $rate;
        return $this;
    }

    public function setUom(string $uoM): self
    {
        $this->uoM = $uoM;
        return $this;
    }

    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function setTotalValues(float $totalValues): self
    {
        $this->totalValues = $totalValues;
        return $this;
    }

    public function setValueSalesExcludingST(float $valueSalesExcludingST): self
    {
        $this->valueSalesExcludingST = $valueSalesExcludingST;
        return $this;
    }

    public function setFixedNotifiedValueOrRetailPrice(float $fixedNotifiedValueOrRetailPrice): self
    {
        $this->fixedNotifiedValueOrRetailPrice = $fixedNotifiedValueOrRetailPrice;
        return $this;
    }

    public function setSalesTaxApplicable(float $salesTaxApplicable): self
    {
        $this->salesTaxApplicable = $salesTaxApplicable;
        return $this;
    }

    public function setSalesTaxWithheldAtSource(float $salesTaxWithheldAtSource): self
    {
        $this->salesTaxWithheldAtSource = $salesTaxWithheldAtSource;
        return $this;
    }

    public function setExtraTax(float $extraTax): self
    {
        $this->extraTax = $extraTax;
        return $this;
    }

    public function setFurtherTax(float $furtherTax): self
    {
        $this->furtherTax = $furtherTax;
        return $this;
    }

    public function setSroScheduleNo(string $sroScheduleNo): self
    {
        $this->sroScheduleNo = $sroScheduleNo;
        return $this;
    }

    public function setFedPayable(float $fedPayable): self
    {
        $this->fedPayable = $fedPayable;
        return $this;
    }

    public function setDiscount(float $discount): self
    {
        $this->discount = $discount;
        return $this;
    }

    public function setSaleType(string $saleType): self
    {
        $this->saleType = $saleType;
        return $this;
    }

    public function setSroItemSerialNo(string $sroItemSerialNo): self
    {
        $this->sroItemSerialNo = $sroItemSerialNo;
        return $this;
    }

    public function build(): InvoiceItemData
    {
        $this->validate();

        return new InvoiceItemData(
            hsCode: $this->hsCode,
            productDescription: $this->productDescription,
            rate: $this->rate,
            uoM: $this->uoM,
            quantity: $this->quantity,
            totalValues: $this->totalValues,
            valueSalesExcludingST: $this->valueSalesExcludingST,
            fixedNotifiedValueOrRetailPrice: $this->fixedNotifiedValueOrRetailPrice,
            salesTaxApplicable: $this->salesTaxApplicable,
            salesTaxWithheldAtSource: $this->salesTaxWithheldAtSource,
            extraTax: $this->extraTax,
            furtherTax: $this->furtherTax,
            sroScheduleNo: $this->sroScheduleNo,
            fedPayable: $this->fedPayable,
            discount: $this->discount,
            saleType: $this->saleType,
            sroItemSerialNo: $this->sroItemSerialNo
        );
    }

    private function validate(): void
    {
        $requiredFields = [
            'hsCode' => $this->hsCode,
            'productDescription' => $this->productDescription,
            'rate' => $this->rate,
            'uoM' => $this->uoM,
            'quantity' => $this->quantity,
            'valueSalesExcludingST' => $this->valueSalesExcludingST,
            'salesTaxApplicable' => $this->salesTaxApplicable,
        ];

        foreach ($requiredFields as $field => $value) {
            if ($value === null || $value === '') {
                throw new FbrDigitalInvoicingException("Required field '$field' is missing");
            }
        }

        // Validate HS Code format
        if (!preg_match('/^\d{4}\.\d{4}$/', $this->hsCode)) {
            throw new FbrDigitalInvoicingException('HS Code must be in format XXXX.XXXX');
        }

        // Validate numeric fields
        if ($this->quantity < 0) {
            throw new FbrDigitalInvoicingException('Quantity cannot be negative');
        }

        if ($this->valueSalesExcludingST < 0) {
            throw new FbrDigitalInvoicingException('Value Sales Excluding ST cannot be negative');
        }

        if ($this->salesTaxApplicable < 0) {
            throw new FbrDigitalInvoicingException('Sales Tax Applicable cannot be negative');
        }
    }
}