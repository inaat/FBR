<?php

namespace Fbr\DigitalInvoicing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    protected $table = 'fbr_invoice_items';

    protected $fillable = [
        'fbr_invoice_id',
        'fbr_item_invoice_number',
        'hs_code',
        'product_description',
        'rate',
        'uom',
        'quantity',
        'total_values',
        'value_sales_excluding_st',
        'fixed_notified_value_or_retail_price',
        'sales_tax_applicable',
        'sales_tax_withheld_at_source',
        'extra_tax',
        'further_tax',
        'sro_schedule_no',
        'fed_payable',
        'discount',
        'sale_type',
        'sro_item_serial_no',
        'status',
        'error_message'
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'total_values' => 'decimal:2',
        'value_sales_excluding_st' => 'decimal:2',
        'fixed_notified_value_or_retail_price' => 'decimal:2',
        'sales_tax_applicable' => 'decimal:2',
        'sales_tax_withheld_at_source' => 'decimal:2',
        'extra_tax' => 'decimal:2',
        'further_tax' => 'decimal:2',
        'fed_payable' => 'decimal:2',
        'discount' => 'decimal:2'
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'fbr_invoice_id');
    }

    public function isValid(): bool
    {
        return $this->status === 'valid';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isInvalid(): bool
    {
        return $this->status === 'invalid';
    }

    public function getFbrItemInvoiceNumber(): ?string
    {
        return $this->fbr_item_invoice_number;
    }

    public function getTotalAmount(): float
    {
        return $this->value_sales_excluding_st + $this->sales_tax_applicable + $this->further_tax + $this->extra_tax + $this->fed_payable - $this->discount;
    }

    public function getNetAmount(): float
    {
        return $this->value_sales_excluding_st + $this->sales_tax_applicable - $this->sales_tax_withheld_at_source;
    }
}