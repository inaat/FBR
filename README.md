# FBR Digital Invoicing Laravel Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fbr/digital-invoicing.svg?style=flat-square)](https://packagist.org/packages/fbr/digital-invoicing)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/inaat/fbr-digital-invoicing/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/inaat/fbr-digital-invoicing/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/fbr/digital-invoicing.svg?style=flat-square)](https://packagist.org/packages/fbr/digital-invoicing)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/packagist/php-v/fbr/digital-invoicing?style=flat-square)](https://packagist.org/packages/fbr/digital-invoicing)

A comprehensive Laravel package for integrating with Pakistan's Federal Board of Revenue (FBR) Digital Invoicing System API v1.12.

## Features

- ✅ **Complete FBR API Integration** - Full support for invoice submission and validation
- ✅ **Reference Data APIs** - Access to provinces, UOM, HS codes, SRO items, and more
- ✅ **Multiple Environments** - Sandbox and production environment support
- ✅ **Fluent Builder Pattern** - Easy-to-use invoice and item builders
- ✅ **Database Models** - Laravel Eloquent models with relationships
- ✅ **Background Processing** - Queue-based invoice submission with retry logic
- ✅ **Comprehensive Validation** - Built-in validation for all invoice data
- ✅ **Error Handling** - Detailed error reporting and logging
- ✅ **QR Code Helpers** - Generate QR codes as per FBR requirements
- ✅ **Artisan Commands** - Sync reference data and manage invoices
- ✅ **28 Scenarios Support** - All FBR sandbox testing scenarios
- ✅ **STATL Integration** - Registration status validation
- ✅ **Facade Support** - Clean Laravel facade interface

## Installation

Install the package via Composer:

```bash
composer require fbr/digital-invoicing
```

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Fbr\DigitalInvoicing\FbrDigitalInvoicingServiceProvider" --tag="fbr-config"
```

Publish and run the migrations:

```bash
php artisan vendor:publish --provider="Fbr\DigitalInvoicing\FbrDigitalInvoicingServiceProvider" --tag="fbr-migrations"
php artisan migrate
```

## Configuration

Add the following to your `.env` file:

```env
# FBR API Configuration
FBR_BEARER_TOKEN=your_5_year_validity_token_here
FBR_SANDBOX=true
FBR_API_TIMEOUT=30
FBR_RETRY_ATTEMPTS=3

# Logging
FBR_LOGGING_ENABLED=true
FBR_LOG_CHANNEL=daily
FBR_LOG_LEVEL=info
```

The configuration file `config/fbr-digital-invoicing.php` contains all available options:

```php
return [
    'bearer_token' => env('FBR_BEARER_TOKEN', ''),
    'sandbox' => env('FBR_SANDBOX', true),
    'urls' => [
        'sandbox' => 'https://gw.fbr.gov.pk/di_data/v1/di/',
        'production' => 'https://gw.fbr.gov.pk/di_data/v1/di/',
        'reference' => 'https://gw.fbr.gov.pk/pdi/v1/',
        'statl' => 'https://gw.fbr.gov.pk/dist/v1/',
    ],
    'qr_code' => [
        'version' => '2.0',
        'dimensions' => '1.0x1.0 Inch',
        'size' => '25x25',
    ],
    'timeout' => env('FBR_API_TIMEOUT', 30),
    'retry_attempts' => env('FBR_RETRY_ATTEMPTS', 3),
    'logging' => [
        'enabled' => env('FBR_LOGGING_ENABLED', true),
        'channel' => env('FBR_LOG_CHANNEL', 'daily'),
        'level' => env('FBR_LOG_LEVEL', 'info'),
    ],
];
```

## Quick Start

### Basic Invoice Creation and Submission

```php
use Fbr\DigitalInvoicing\Facades\FbrDigitalInvoicing;
use Fbr\DigitalInvoicing\Builders\InvoiceBuilder;
use Fbr\DigitalInvoicing\Builders\InvoiceItemBuilder;

// Create invoice item
$item = (new InvoiceItemBuilder())
    ->setHsCode('0101.2100')
    ->setProductDescription('Test Product')
    ->setRate('18%')
    ->setUom('Numbers, pieces, units')
    ->setQuantity(1.0)
    ->setValueSalesExcludingST(1000.0)
    ->setSalesTaxApplicable(180.0)
    ->setSaleType('Goods at standard rate (default)')
    ->build();

// Create invoice
$invoice = (new InvoiceBuilder())
    ->setInvoiceType('Sale Invoice')
    ->setInvoiceDate('2025-08-04')
    ->setSeller('0786909', 'My Company', 'Sindh', 'Karachi')
    ->setBuyer('1000000000000', 'Customer Ltd', 'Punjab', 'Lahore', 'Registered')
    ->setScenarioId('SN001') // Only for sandbox
    ->addItem($item)
    ->build();

// Submit to FBR
$response = FbrDigitalInvoicing::postInvoiceData($invoice);

if ($response->isValid()) {
    echo "Invoice submitted successfully!";
    echo "FBR Invoice Number: " . $response->invoiceNumber;
} else {
    echo "Submission failed: " . implode(', ', $response->getErrors());
}
```

### Validation Before Submission

```php
// First validate the invoice
$validationResponse = FbrDigitalInvoicing::validateInvoiceData($invoice);

if ($validationResponse->isValid()) {
    // Now submit
    $submitResponse = FbrDigitalInvoicing::postInvoiceData($invoice);
    
    if ($submitResponse->isValid()) {
        echo "Invoice submitted successfully!";
    }
}
```

### Using Database Models

```php
use Fbr\DigitalInvoicing\Models\Invoice;
use Fbr\DigitalInvoicing\Jobs\SubmitInvoiceJob;

// Save invoice to database
$invoice = Invoice::createFromInvoiceData($invoiceData);

// Submit via background job
SubmitInvoiceJob::dispatch($invoice)->onQueue('fbr-invoices');

// Check status later
if ($invoice->isValid()) {
    echo "Invoice processed successfully!";
    echo "FBR Number: " . $invoice->getFbrInvoiceNumber();
}
```

## Supported Scenarios

The package supports all 28 official FBR sandbox testing scenarios based on "DI Scenarios Description for Sandbox Testing" v1.11:

### Manufacturing & Standard Sales (SN001-SN009)

**SN001: Sale of Standard Rate Goods to Registered Buyers**
- Sale of goods subject to the standard sales tax rate made to sales tax registered buyers
- Buyers can usually claim input tax credits
- Rate: 18% | Buyer: Registered | Example: General manufacturing sales

**SN002: Sale of Standard Rate Goods to Unregistered Buyers**  
- Standard rate goods sold to buyers who are not registered for sales tax (B2C sales)
- Rate: 18% | Buyer: Unregistered | Example: Retail sales to consumers

**SN003: Sale of Steel (Melted and Re-Rolled) - Billets, Ingots and Long Bars**
- Steel sector governed by strict traceability and sector-specific rules
- Rate: 18% | Buyer: Unregistered | Example: Steel manufacturing

**SN004: Sale of Steel Scrap by Ship Breakers**
- Ship breakers dismantle old ships and recover scrap steel with special tax treatment
- Rate: 18% | Buyer: Unregistered | Example: Ship breaking industry

**SN005: Sales of Reduced Rate Goods (Eighth Schedule)**
- Goods taxed at reduced sales tax rate to encourage affordability
- Rate: 1% | Buyer: Unregistered | Example: Essential commodities, basic food items

**SN006: Sale of Exempt Goods (Sixth Schedule)**
- Goods listed in the Sixth Schedule are exempt from sales tax
- Rate: Exempt | Buyer: Registered | Example: Agricultural products, medicines

**SN007: Sale Of Zero-Rated Goods (Fifth Schedule)**
- Zero-rated goods charged at 0% but seller can claim input tax credits
- Rate: 0% | Buyer: Unregistered | Example: Exported goods

**SN008: Sale of 3rd Schedule Goods**
- Items subject to sales tax based on printed retail price rather than transaction value
- Rate: 18% | Buyer: Unregistered | Example: Branded consumer products

**SN009: Purchase From Registered Cotton Ginners**
- Purchases from registered cotton ginners, subject to specific cotton trade taxation rules
- Rate: 18% | Buyer: Registered | Example: Cotton industry trade

### Services & Specialized Industries (SN010-SN019)

**SN010: Sale Of Telecom Services by Mobile Operators**
- Mobile operators providing telecom services (calls, data, SMS)
- Rate: 17% | Buyer: Unregistered | Example: Telecom services

**SN011: Sale of Steel through Toll Manufacturing - Billets, Ingots and Long Bars**
- Third-party processing raw steel into finished products
- Rate: 18% | Buyer: Unregistered | Example: Steel toll manufacturing

**SN012: Sale Of Petroleum Products**
- Petroleum products with distinct sales tax rates or federal excise duties
- Rate: 1.43% | Buyer: Unregistered | Example: Petrol, diesel, lubricants

**SN013: Sale Of Electricity to Retailers**
- Selling electricity to retailers who distribute to end consumers
- Rate: 5% | Buyer: Unregistered | Example: Power distribution

**SN014: Sale of Gas to CNG Stations**
- Natural gas sold to CNG filling stations with special tax treatment
- Rate: 18% | Buyer: Unregistered | Example: CNG supply

**SN015: Sale of Mobile Phones**
- Sales of mobile handsets with additional duties or regulatory charges
- Rate: 18% | Buyer: Unregistered | Example: Mobile phone sales

**SN016: Processing / Conversion of Goods**
- Services where raw materials are converted into finished products
- Rate: 5% | Buyer: Unregistered | Example: Manufacturing services

**SN017: Sale of Goods Where FED Is Charged in ST Mode**
- Federal Excise Duty collected through the sales tax system
- Rate: 8% | Buyer: Unregistered | Example: FED-applicable goods

**SN018: Sale Of Services Where FED Is Charged in ST Mode**
- Services (advertisement, franchise, insurance) liable to FED via sales tax framework
- Rate: 8% | Buyer: Unregistered | Example: Advertisement services

**SN019: Sale of Services (as per ICT Ordinance)**
- IT services taxed under ICT ordinance with variations in rates or exemptions
- Rate: 5% | Buyer: Unregistered | Example: Software development, IT consultancy

### Specialized Products & Retail (SN020-SN028)

**SN020: Sale of Electric Vehicles**
- Electric vehicles incentivized through reduced sales tax rates
- Rate: 1% | Buyer: Unregistered | Example: EV sales

**SN021: Sale of Cement /Concrete Block**
- Cement and concrete blocks with strict regulation due to environmental impact
- Rate: Rs.3 per unit | Buyer: Unregistered | Example: Construction materials

**SN022: Sale of Potassium Chlorate**
- Sensitive chemical subject to fixed tax per kilogram rather than value
- Rate: 18% + Rs.60 per kg | Buyer: Unregistered | Example: Chemical manufacturing

**SN023: Sale of CNG**
- Compressed Natural Gas with regulated pricing and specific tax treatments
- Rate: Rs.200 per unit | Buyer: Unregistered | Example: CNG stations

**SN024: Sale Of Goods Listed in SRO 297(1)/2023**
- Specific goods subject to reduced, conditional, or fixed-rate taxation
- Rate: 25% | Buyer: Unregistered | Example: Solar equipment, medical devices

**SN025: Drugs Sold at Fixed ST Rate Under Serial 81 Of Eighth Schedule Table 1**
- Pharmaceutical products at fixed sales tax rate to make medicines affordable
- Rate: 0% | Buyer: Unregistered | Example: Essential medicines

**SN026: Sale Of Goods at Standard Rate to End Consumers by Retailers**
- Retailers selling taxable goods directly to end consumers
- Rate: 18% | Buyer: Registered | Example: Retail sales

**SN027: Sale Of 3rd Schedule Goods to End Consumers by Retailers**
- Retailers selling 3rd Schedule goods based on maximum retail price (MRP)
- Rate: 18% | Buyer: Registered | Example: FMCG retail

**SN028: Sale of Goods at Reduced Rate to End Consumers by Retailers**
- Essential goods at reduced tax rate to keep vital products affordable
- Rate: 1% | Buyer: Registered | Example: Baby milk, books

### Using Scenarios in Code

```php
use Fbr\DigitalInvoicing\Constants\Scenarios;

// Get scenario details
$scenario = Scenarios::getScenario('SN001');
echo $scenario['description'];

// Generate invoice for specific scenario
$invoice = Scenarios::generateScenarioInvoice('SN001');

// Get scenarios by business type
$manufacturerScenarios = Scenarios::getScenariosByBusinessType('manufacturer');
$retailerScenarios = Scenarios::getScenariosByBusinessType('retailer');
$serviceScenarios = Scenarios::getScenariosByBusinessType('service_provider');

// Get sample data for testing
$sampleData = Scenarios::getScenarioSampleData('SN001');
```

## Reference Data APIs

Access FBR reference data easily:

```php
// Get provinces
$provinces = FbrDigitalInvoicing::getProvinces();

// Get UOM codes  
$uoms = FbrDigitalInvoicing::getUomCodes();

// Get HS codes
$hsCodes = FbrDigitalInvoicing::getItemDescCodes();

// Get SRO items
$sroItems = FbrDigitalInvoicing::getSroItemCodes();

// Check STATL registration
$statlStatus = FbrDigitalInvoicing::checkStatl('0786909', '2025-08-04');

// Get registration type
$regType = FbrDigitalInvoicing::getRegistrationType('0786909');
```

## Artisan Commands

### Sync Reference Data

```bash
# Sync all reference data
php artisan fbr:sync

# Sync specific data types
php artisan fbr:sync --type=provinces
php artisan fbr:sync --type=uom
php artisan fbr:sync --type=hscodes

# Force refresh cached data
php artisan fbr:sync --force

# Submit pending invoices
php artisan fbr:sync --submit-pending
```

### Available sync types:
- `provinces` - Province codes
- `uom` - Unit of measurement codes  
- `doctypes` - Document type codes
- `hscodes` - Harmonized system codes
- `sroitems` - SRO item codes
- `transtypes` - Transaction type codes
- `all` - All reference data (default)

## Queue Configuration

For background processing, configure your queues:

```php
// config/queue.php
'connections' => [
    'database' => [
        // ... other config
        'queue' => 'fbr-invoices', // Dedicated queue for FBR jobs
    ],
],
```

Run queue worker:

```bash
php artisan queue:work --queue=fbr-invoices
```

## QR Code Generation

Generate QR codes as per FBR requirements:

```php
use Fbr\DigitalInvoicing\Helpers\QrCodeHelper;

// Generate QR data from invoice model
$qrData = QrCodeHelper::generateQrDataString($invoice);

// Generate QR data from response
$qrData = QrCodeHelper::generateQrDataFromResponse($response, $sellerNtn, $buyerNtn, $totalAmount);

// Get QR specifications
$specs = QrCodeHelper::getQrSpecifications();
// Returns: ['version' => '2.0', 'size' => '25x25', 'dimensions' => '1.0x1.0 Inch']

// Validate and decode QR data
if (QrCodeHelper::validateQrData($qrData)) {
    $decoded = QrCodeHelper::decodeQrData($qrData);
}
```

## Error Handling

The package provides comprehensive error handling:

```php
use Fbr\DigitalInvoicing\Exceptions\FbrDigitalInvoicingException;

try {
    $response = FbrDigitalInvoicing::postInvoiceData($invoice);
} catch (FbrDigitalInvoicingException $e) {
    // FBR API specific errors
    echo "FBR Error: " . $e->getMessage();
    echo "HTTP Status: " . $e->getCode();
} catch (\Exception $e) {
    // General errors
    echo "Error: " . $e->getMessage();
}

// Check response for validation errors
if (!$response->isValid()) {
    foreach ($response->getErrors() as $error) {
        echo "Error: " . $error;
    }
}
```

## Database Models

### Invoice Model

```php
$invoice = Invoice::find(1);

// Check status
$invoice->isPending();    // Check if pending
$invoice->isSubmitted();  // Check if submitted  
$invoice->isValid();      // Check if valid
$invoice->isFailed();     // Check if failed

// Get FBR details
$invoice->getFbrInvoiceNumber();
$invoice->getValidationErrors();

// Convert to DTO
$invoiceData = $invoice->toInvoiceData();

// Relationships
$invoice->items;  // Get invoice items
```

### InvoiceItem Model

```php
$item = InvoiceItem::find(1);

// Check status
$item->isValid();
$item->isPending();
$item->isInvalid();

// Calculate amounts
$item->getTotalAmount();  // Total including all taxes
$item->getNetAmount();    // Net after withholding

// Get FBR details
$item->getFbrItemInvoiceNumber();

// Relationships
$item->invoice;  // Get parent invoice
```

## Testing

The package includes comprehensive tests:

```bash
composer test
```

## Example Integration

See the `examples/basic-usage.php` file for comprehensive usage examples covering:

1. Basic invoice creation and submission
2. Validation before submission  
3. Using database models and background jobs
4. Fetching reference data
5. Creating scenario-based invoices
6. Checking registration status

## Error Codes

The package handles all FBR error codes as documented in the API specification:

### Sales Error Codes (0001-0300, 0401-0402)
### Purchase Error Codes (0156-0177)

Common error codes:
- `0001` - Seller not registered for sales tax
- `0002` - Invalid Buyer Registration No or NTN
- `0005` - Invalid date format
- `0052` - Invalid HS Code
- `0401` - Unauthorized seller access
- `0402` - Unauthorized buyer access

## API Rate Limiting

The package includes built-in retry logic with exponential backoff:

- **Timeout**: 30 seconds (configurable)
- **Retry Attempts**: 3 (configurable)
- **Backoff**: Progressive delay between retries
- **Server Errors**: Automatically retried
- **Client Errors**: Not retried (4xx status codes)

## Logging

All API interactions are logged when enabled:

```php
// Check logs for detailed API interactions
tail -f storage/logs/laravel.log
```

Log entries include:
- Request/response details
- Error information
- Performance metrics
- Retry attempts

## Production Considerations

1. **Environment Configuration**:
   ```env
   FBR_SANDBOX=false  # Switch to production
   ```

2. **Queue Workers**: Set up supervised queue workers
3. **Monitoring**: Monitor queue jobs and failed jobs
4. **Caching**: Reference data is cached for 30 days by default
5. **Logging**: Review logs regularly for errors
6. **Rate Limiting**: Respect FBR API rate limits

## Security

- Never commit your bearer token to version control
- Use environment variables for all sensitive configuration
- Regularly rotate your bearer token (5-year validity)
- Monitor API usage and unauthorized access attempts
- Follow Laravel security best practices

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Inayat Ullah](https://github.com/inaat)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Support

- 📧 Email: inayatullahkks@gmail.com
- 📖 Documentation: [Full Documentation](docs/)
- 🐛 Issues: [GitHub Issues](https://github.com/inaat/fbr-digital-invoicing/issues)
- 💬 Discussions: [GitHub Discussions](https://github.com/inaat/fbr-digital-invoicing/discussions)

---

**Disclaimer**: This package is not officially affiliated with FBR Pakistan. It is a third-party integration built according to the official FBR Digital Invoicing API specification v1.12.