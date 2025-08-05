<?php

namespace Fbr\DigitalInvoicing\Helpers;

use Fbr\DigitalInvoicing\Models\Invoice;
use Fbr\DigitalInvoicing\DTOs\InvoiceResponse;

class QrCodeHelper
{
    /**
     * Generate QR code data string for FBR invoice
     */
    public static function generateQrDataString(Invoice $invoice): string
    {
        if (!$invoice->isSubmitted() || !$invoice->getFbrInvoiceNumber()) {
            return '';
        }

        $data = [
            'inv' => $invoice->getFbrInvoiceNumber(),
            'date' => $invoice->submitted_at?->format('Y-m-d H:i:s') ?? $invoice->created_at->format('Y-m-d H:i:s'),
            'seller' => $invoice->seller_ntn_cnic,
            'buyer' => $invoice->buyer_ntn_cnic,
            'amount' => $invoice->items->sum(fn($item) => $item->getTotalAmount()),
            'status' => $invoice->isValid() ? 'Valid' : 'Invalid'
        ];

        return base64_encode(json_encode($data));
    }

    /**
     * Generate QR code data from InvoiceResponse
     */
    public static function generateQrDataFromResponse(InvoiceResponse $response, string $sellerNtn, string $buyerNtn, float $totalAmount): string
    {
        if (!$response->invoiceNumber) {
            return '';
        }

        $data = [
            'inv' => $response->invoiceNumber,
            'date' => $response->dated,
            'seller' => $sellerNtn,
            'buyer' => $buyerNtn,
            'amount' => $totalAmount,
            'status' => $response->isValid() ? 'Valid' : 'Invalid'
        ];

        return base64_encode(json_encode($data));
    }

    /**
     * Get QR code specifications as per FBR requirements
     */
    public static function getQrSpecifications(): array
    {
        return config('fbr-digital-invoicing.qr_code', [
            'version' => '2.0',
            'size' => '25x25',
            'dimensions' => '1.0 x 1.0 Inch',
            'format' => 'PNG/SVG recommended'
        ]);
    }

    /**
     * Get FBR Digital Invoicing System logo path/URL
     */
    public static function getFbrLogoInfo(): array
    {
        return [
            'description' => 'FBR Digital Invoicing System logo must be printed on each invoice',
            'placement' => 'Top of invoice with QR code',
            'format' => 'High resolution PNG/SVG recommended',
            'note' => 'Logo should be obtained from FBR official documentation'
        ];
    }

    /**
     * Validate QR code data
     */
    public static function validateQrData(string $qrData): bool
    {
        if (empty($qrData)) {
            return false;
        }

        $decoded = base64_decode($qrData, true);
        if ($decoded === false) {
            return false;
        }

        $data = json_decode($decoded, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }

        $requiredKeys = ['inv', 'date', 'seller', 'buyer', 'amount', 'status'];
        foreach ($requiredKeys as $key) {
            if (!isset($data[$key])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Decode QR code data
     */
    public static function decodeQrData(string $qrData): ?array
    {
        if (!self::validateQrData($qrData)) {
            return null;
        }

        $decoded = base64_decode($qrData);
        return json_decode($decoded, true);
    }
}