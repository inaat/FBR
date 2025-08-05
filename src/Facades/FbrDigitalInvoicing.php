<?php

namespace Fbr\DigitalInvoicing\Facades;

use Illuminate\Support\Facades\Facade;
use Fbr\DigitalInvoicing\DTOs\InvoiceData;
use Fbr\DigitalInvoicing\DTOs\InvoiceResponse;

/**
 * @method static InvoiceResponse postInvoiceData(InvoiceData $invoiceData)
 * @method static InvoiceResponse validateInvoiceData(InvoiceData $invoiceData)
 * @method static array getProvinces()
 * @method static array getDocumentTypeCodes()
 * @method static array getItemDescCodes()
 * @method static array getSroItemCodes()
 * @method static array getTransactionTypeCodes()
 * @method static array getUomCodes()
 * @method static array getSroSchedule(int $rateId, string $date, int $originationSupplierCsv = 1)
 * @method static array getSaleTypeToRate(string $date, int $transTypeId, int $originationSupplier)
 * @method static array getHsUom(string $hsCode, int $annexureId)
 * @method static array getSroItems(string $date, int $sroId)
 * @method static array checkStatl(string $regno, string $date)
 * @method static array getRegistrationType(string $registrationNo)
 */
class FbrDigitalInvoicing extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'fbr-digital-invoicing';
    }
}