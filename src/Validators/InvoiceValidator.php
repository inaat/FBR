<?php
// File: src/Validators/InvoiceValidator.php
namespace Fbr\DigitalInvoicing\Validators;

use Fbr\DigitalInvoicing\Exceptions\FbrDigitalInvoicingException;

class InvoiceValidator
{
    public static function validate(array $invoiceData): void
    {
        // Validate required fields
        $requiredFields = [
            'invoiceType',
            'invoiceDate',
            'sellerNTNCNIC',
            'sellerBusinessName',
            'sellerProvince',
            'sellerAddress',
            'buyerBusinessName',
            'buyerProvince',
            'buyerAddress',
            'buyerRegistrationType',
            'items'
        ];

        foreach ($requiredFields as $field) {
            if (!isset($invoiceData[$field]) || empty($invoiceData[$field])) {
                throw new FbrDigitalInvoicingException("Field {$field} is required");
            }
        }

        // Validate NTN/CNIC format
        self::validateNtnCnic($invoiceData['sellerNTNCNIC'], 'seller');
        
        if (isset($invoiceData['buyerNTNCNIC']) && !empty($invoiceData['buyerNTNCNIC'])) {
            self::validateNtnCnic($invoiceData['buyerNTNCNIC'], 'buyer');
        }

        // Validate date format
        if (!self::isValidDate($invoiceData['invoiceDate'])) {
            throw new FbrDigitalInvoicingException("Invalid date format. Use YYYY-MM-DD");
        }

        // Validate items
        if (empty($invoiceData['items'])) {
            throw new FbrDigitalInvoicingException("At least one item is required");
        }

        foreach ($invoiceData['items'] as $index => $item) {
            self::validateItem($item, $index);
        }
    }

    private static function validateNtnCnic(string $ntnCnic, string $type): void
    {
        $length = strlen($ntnCnic);
        if ($length !== 7 && $length !== 9 && $length !== 13) {
            throw new FbrDigitalInvoicingException(
                ucfirst($type) . " NTN/CNIC must be 7 digits (NTN), 9 digits (NTN), or 13 digits (CNIC)"
            );
        }
    }

    private static function isValidDate(string $date): bool
    {
        $format = 'Y-m-d';
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    private static function validateItem(array $item, int $index): void
    {
        $requiredItemFields = [
            'hsCode',
            'productDescription',
            'rate',
            'uoM',
            'quantity',
            'totalValues',
            'valueSalesExcludingST',
            'fixedNotifiedValueOrRetailPrice',
            'salesTaxApplicable',
            'salesTaxWithheldAtSource',
            'saleType'
        ];

        foreach ($requiredItemFields as $field) {
            if (!isset($item[$field])) {
                throw new FbrDigitalInvoicingException(
                    "Item {$index}: Field {$field} is required"
                );
            }
        }
    }
}