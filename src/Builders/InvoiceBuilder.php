<?php

namespace Fbr\DigitalInvoicing\Builders;

use Fbr\DigitalInvoicing\DTOs\InvoiceData;
use Fbr\DigitalInvoicing\DTOs\InvoiceItemData;
use Fbr\DigitalInvoicing\Exceptions\FbrDigitalInvoicingException;

class InvoiceBuilder
{
    private ?string $invoiceType = null;
    private ?string $invoiceDate = null;
    private ?string $sellerNTNCNIC = null;
    private ?string $sellerBusinessName = null;
    private ?string $sellerProvince = null;
    private ?string $sellerAddress = null;
    private ?string $buyerNTNCNIC = null;
    private ?string $buyerBusinessName = null;
    private ?string $buyerProvince = null;
    private ?string $buyerAddress = null;
    private ?string $buyerRegistrationType = null;
    private string $invoiceRefNo = '';
    private ?string $scenarioId = null;
    private array $items = [];

    public function setInvoiceType(string $invoiceType): self
    {
        $this->invoiceType = $invoiceType;
        return $this;
    }

    public function setInvoiceDate(string $invoiceDate): self
    {
        $this->invoiceDate = $invoiceDate;
        return $this;
    }

    public function setSeller(string $ntnCnic, string $businessName, string $province, string $address): self
    {
        $this->sellerNTNCNIC = $ntnCnic;
        $this->sellerBusinessName = $businessName;
        $this->sellerProvince = $province;
        $this->sellerAddress = $address;
        return $this;
    }

    public function setBuyer(string $ntnCnic, string $businessName, string $province, string $address, string $registrationType): self
    {
        $this->buyerNTNCNIC = $ntnCnic;
        $this->buyerBusinessName = $businessName;
        $this->buyerProvince = $province;
        $this->buyerAddress = $address;
        $this->buyerRegistrationType = $registrationType;
        return $this;
    }

    public function setInvoiceRefNo(string $invoiceRefNo): self
    {
        $this->invoiceRefNo = $invoiceRefNo;
        return $this;
    }

    public function setScenarioId(string $scenarioId): self
    {
        $this->scenarioId = $scenarioId;
        return $this;
    }

    public function addItem(InvoiceItemData $item): self
    {
        $this->items[] = $item;
        return $this;
    }

    public function addItems(array $items): self
    {
        foreach ($items as $item) {
            if (!$item instanceof InvoiceItemData) {
                throw new FbrDigitalInvoicingException('All items must be instances of InvoiceItemData');
            }
            $this->addItem($item);
        }
        return $this;
    }

    public function build(): InvoiceData
    {
        $this->validate();

        return new InvoiceData(
            invoiceType: $this->invoiceType,
            invoiceDate: $this->invoiceDate,
            sellerNTNCNIC: $this->sellerNTNCNIC,
            sellerBusinessName: $this->sellerBusinessName,
            sellerProvince: $this->sellerProvince,
            sellerAddress: $this->sellerAddress,
            buyerNTNCNIC: $this->buyerNTNCNIC,
            buyerBusinessName: $this->buyerBusinessName,
            buyerProvince: $this->buyerProvince,
            buyerAddress: $this->buyerAddress,
            buyerRegistrationType: $this->buyerRegistrationType,
            invoiceRefNo: $this->invoiceRefNo,
            scenarioId: $this->scenarioId,
            items: $this->items
        );
    }

    private function validate(): void
    {
        $requiredFields = [
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
        ];

        foreach ($requiredFields as $field => $value) {
            if (empty($value)) {
                throw new FbrDigitalInvoicingException("Required field '$field' is missing");
            }
        }

        if (empty($this->items)) {
            throw new FbrDigitalInvoicingException('At least one invoice item is required');
        }

        // Validate date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->invoiceDate)) {
            throw new FbrDigitalInvoicingException('Invoice date must be in YYYY-MM-DD format');
        }

        // Validate NTN/CNIC format
        if (!$this->isValidNtnCnic($this->sellerNTNCNIC)) {
            throw new FbrDigitalInvoicingException('Seller NTN/CNIC format is invalid');
        }

        if ($this->buyerRegistrationType === 'Registered' && !$this->isValidNtnCnic($this->buyerNTNCNIC)) {
            throw new FbrDigitalInvoicingException('Buyer NTN/CNIC format is invalid');
        }
    }

    private function isValidNtnCnic(string $value): bool
    {
        // NTN: 7 or 9 digits, CNIC: 13 digits
        return preg_match('/^\d{7}$|^\d{9}$|^\d{13}$/', $value);
    }
}