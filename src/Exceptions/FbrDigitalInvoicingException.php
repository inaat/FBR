<?php
namespace Fbr\DigitalInvoicing\Exceptions;

use Exception;

class FbrDigitalInvoicingException extends Exception
{
    protected array $errorCodes = [
        // Sales Error Codes
        '0001' => 'Seller not registered for sales tax',
        '0002' => 'Invalid Buyer Registration No or NTN',
        '0003' => 'Provide proper invoice type',
        '0005' => 'Please provide date in valid format',
        '0019' => 'Please provide HSCode',
        '0046' => 'Provide rate',
        '0401' => 'Unauthorized seller access token',
        '0402' => 'Unauthorized buyer access token',
        // Add more error codes as needed
    ];

    public function getErrorDescription(): string
    {
        return $this->errorCodes[$this->getCode()] ?? 'Unknown error';
    }
}