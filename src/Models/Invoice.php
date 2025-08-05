<?php

namespace Fbr\DigitalInvoicing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Fbr\DigitalInvoicing\DTOs\InvoiceData;
use Fbr\DigitalInvoicing\DTOs\InvoiceItemData;

class Invoice extends Model
{
    protected $table = 'fbr_invoices';

    protected $fillable = [
        'fbr_invoice_number',
        'invoice_type',
        'invoice_date',
        'seller_ntn_cnic',
        'seller_business_name',
        'seller_province',
        'seller_address',
        'buyer_ntn_cnic',
        'buyer_business_name',
        'buyer_province',
        'buyer_address',
        'buyer_registration_type',
        'invoice_ref_no',
        'scenario_id',
        'validation_response',
        'status',
        'error_message',
        'submitted_at'
    ];

    protected $casts = [
        'validation_response' => 'array',
        'invoice_date' => 'date',
        'submitted_at' => 'datetime'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'fbr_invoice_id');
    }

    public function isValid(): bool
    {
        return $this->status === 'submitted' && 
               isset($this->validation_response['validationResponse']['statusCode']) &&
               $this->validation_response['validationResponse']['statusCode'] === '00';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function getFbrInvoiceNumber(): ?string
    {
        return $this->validation_response['invoiceNumber'] ?? $this->fbr_invoice_number;
    }

    public function getValidationErrors(): array
    {
        if (!$this->validation_response) {
            return [];
        }

        $errors = [];
        $response = $this->validation_response['validationResponse'] ?? [];

        if (!empty($response['error'])) {
            $errors[] = $response['error'];
        }

        if (!empty($response['invoiceStatuses'])) {
            foreach ($response['invoiceStatuses'] as $status) {
                if (!empty($status['error'])) {
                    $errors[] = "Item {$status['itemSNo']}: {$status['error']}";
                }
            }
        }

        return $errors;
    }

    public function toInvoiceData(): InvoiceData
    {
        $itemsData = $this->items->map(function ($item) {
            return new InvoiceItemData(
                hsCode: $item->hs_code,
                productDescription: $item->product_description,
                rate: $item->rate,
                uoM: $item->uom,
                quantity: $item->quantity,
                totalValues: $item->total_values,
                valueSalesExcludingST: $item->value_sales_excluding_st,
                fixedNotifiedValueOrRetailPrice: $item->fixed_notified_value_or_retail_price,
                salesTaxApplicable: $item->sales_tax_applicable,
                salesTaxWithheldAtSource: $item->sales_tax_withheld_at_source,
                extraTax: $item->extra_tax,
                furtherTax: $item->further_tax,
                sroScheduleNo: $item->sro_schedule_no ?? '',
                fedPayable: $item->fed_payable,
                discount: $item->discount,
                saleType: $item->sale_type,
                sroItemSerialNo: $item->sro_item_serial_no ?? ''
            );
        })->toArray();

        return new InvoiceData(
            invoiceType: $this->invoice_type,
            invoiceDate: $this->invoice_date->format('Y-m-d'),
            sellerNTNCNIC: $this->seller_ntn_cnic,
            sellerBusinessName: $this->seller_business_name,
            sellerProvince: $this->seller_province,
            sellerAddress: $this->seller_address,
            buyerNTNCNIC: $this->buyer_ntn_cnic,
            buyerBusinessName: $this->buyer_business_name,
            buyerProvince: $this->buyer_province,
            buyerAddress: $this->buyer_address,
            buyerRegistrationType: $this->buyer_registration_type,
            invoiceRefNo: $this->invoice_ref_no ?? '',
            scenarioId: $this->scenario_id,
            items: $itemsData
        );
    }

    public static function createFromInvoiceData(InvoiceData $invoiceData): self
    {
        $invoice = self::create([
            'invoice_type' => $invoiceData->invoiceType,
            'invoice_date' => $invoiceData->invoiceDate,
            'seller_ntn_cnic' => $invoiceData->sellerNTNCNIC,
            'seller_business_name' => $invoiceData->sellerBusinessName,
            'seller_province' => $invoiceData->sellerProvince,
            'seller_address' => $invoiceData->sellerAddress,
            'buyer_ntn_cnic' => $invoiceData->buyerNTNCNIC,
            'buyer_business_name' => $invoiceData->buyerBusinessName,
            'buyer_province' => $invoiceData->buyerProvince,
            'buyer_address' => $invoiceData->buyerAddress,
            'buyer_registration_type' => $invoiceData->buyerRegistrationType,
            'invoice_ref_no' => $invoiceData->invoiceRefNo,
            'scenario_id' => $invoiceData->scenarioId,
            'status' => 'pending'
        ]);

        foreach ($invoiceData->items as $itemData) {
            $invoice->items()->create([
                'hs_code' => $itemData->hsCode,
                'product_description' => $itemData->productDescription,
                'rate' => $itemData->rate,
                'uom' => $itemData->uoM,
                'quantity' => $itemData->quantity,
                'total_values' => $itemData->totalValues,
                'value_sales_excluding_st' => $itemData->valueSalesExcludingST,
                'fixed_notified_value_or_retail_price' => $itemData->fixedNotifiedValueOrRetailPrice,
                'sales_tax_applicable' => $itemData->salesTaxApplicable,
                'sales_tax_withheld_at_source' => $itemData->salesTaxWithheldAtSource,
                'extra_tax' => $itemData->extraTax,
                'further_tax' => $itemData->furtherTax,
                'sro_schedule_no' => $itemData->sroScheduleNo,
                'fed_payable' => $itemData->fedPayable,
                'discount' => $itemData->discount,
                'sale_type' => $itemData->saleType,
                'sro_item_serial_no' => $itemData->sroItemSerialNo
            ]);
        }

        return $invoice;
    }
}