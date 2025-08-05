<?php 
<?php

// File: src/Constants/FbrScenarios.php
namespace Fbr\DigitalInvoicing\Constants;

class FbrScenarios
{
    public const SCENARIOS = [
        'SN001' => [
            'name' => 'Sale of Standard Rate Goods to Registered Buyers',
            'description' => 'Sale of goods subject to the standard sales tax rate made to sales tax registered buyers. These buyers can usually claim input tax credits.',
            'sale_type' => 'Goods at standard rate (default)',
            'buyer_registration_type' => 'Registered',
            'rate' => '18%',
            'example_hs_code' => '0101.2100'
        ],
        'SN002' => [
            'name' => 'Sale of Standard Rate Goods to Unregistered Buyers',
            'description' => 'Goods subject to the standard sales tax rate sold to buyers who are not registered for sales tax (B2C sales).',
            'sale_type' => 'Goods at standard rate (default)',
            'buyer_registration_type' => 'Unregistered',
            'rate' => '18%',
            'example_hs_code' => '0101.2100'
        ],
        'SN003' => [
            'name' => 'Sale of Steel (Melted and Re-Rolled)',
            'description' => 'Steel sector governed by strict traceability and sector-specific rules. Billets, Ingots and Long Bars.',
            'sale_type' => 'Steel melting and re-rolling',
            'buyer_registration_type' => 'Unregistered',
            'rate' => '18%',
            'example_hs_code' => '7214.1010'
        ],
        'SN004' => [
            'name' => 'Sale of Steel Scrap by Ship Breakers',
            'description' => 'Ship breakers dismantle old ships and recover scrap steel, with special rates or exemptions.',
            'sale_type' => 'Ship breaking',
            'buyer_registration_type' => 'Unregistered',
            'rate' => '18%',
            'example_hs_code' => '7204.1010'
        ],
        'SN005' => [
            'name' => 'Sales of Reduced Rate Goods (Eighth Schedule)',
            'description' => 'Goods taxed at reduced sales tax rate to encourage affordability or protect consumers.',
            'sale_type' => 'Goods at Reduced Rate',
            'buyer_registration_type' => 'Unregistered',
            'rate' => '1%',
            'example_hs_code' => '0102.2930',
            'sro_schedule_no' => 'EIGHTH SCHEDULE Table 1'
        ],
        'SN006' => [
            'name' => 'Sale of Exempt Goods (Sixth Schedule)',
            'description' => 'Goods listed in the Sixth Schedule are exempt from sales tax.',
            'sale_type' => 'Exempt goods',
            'buyer_registration_type' => 'Registered',
            'rate' => 'Exempt',
            'example_hs_code' => '0102.2930',
            'sro_schedule_no' => '6th Schd Table I'
        ],
        'SN007' => [
            'name' => 'Sale Of Zero-Rated Goods (Fifth Schedule)',
            'description' => 'Zero-rated goods charged at 0% but seller can claim input tax credits.',
            'sale_type' => 'Goods at zero-rate',
            'buyer_registration_type' => 'Unregistered',
            'rate' => '0%',
            'example_hs_code' => '0101.2100',
            'sro_schedule_no' => '327(I)/2008'
        ],
        'SN008' => [
            'name' => 'Sale of 3rd Schedule Goods',
            'description' => 'Items subject to sales tax based on printed retail price rather than transaction value.',
            'sale_type' => '3rd Schedule Goods',
            'buyer_registration_type' => 'Unregistered',
            'rate' => '18%',
            'example_hs_code' => '0101.2100'
        ],
        'SN009' => [
            'name' => 'Purchase From Registered Cotton Ginners',
            'description' => 'Purchases from registered cotton ginners, subject to specific rules under cotton trade taxation.',
            'sale_type' => 'Cotton ginners',
            'buyer_registration_type' => 'Registered',
            'rate' => '18%',
            'example_hs_code' => '0101.2100'
        ],
        'SN010' => [
            'name' => 'Sale Of Telecom Services by Mobile Operators',
            'description' => 'Mobile operators providing telecom services taxed under specific rules separate from goods.',
            'sale_type' => 'Telecommunication services',
            'buyer_registration_type' => 'Unregistered',
            'rate' => '17%',
            'example_hs_code' => '0101.2100'
        ],
        'SN011' => [
            'name' => 'Sale of Steel through Toll Manufacturing',
            'description' => 'Toll manufacturing involves third-party processing raw steel into finished products.',
            'sale_type' => 'Toll Manufacturing',
            'buyer_registration_type' => 'Unregistered',
            'rate' => '18%',
            'example_hs_code' => '7214.9990'
        ],
        'SN012' => [
            'name' => 'Sale Of Petroleum Products',
            'description' => 'Petroleum products with distinct sales tax rates or federal excise duties.',
            'sale_type' => 'Petroleum Products',
            'buyer_registration_type' => 'Unregistered',
            'rate' => '1.43%',
            'example_hs_code' => '0101.2100',
            'sro_schedule_no' => '1450(I)/2021'
        ],
        'SN013' => [
            'name' => 'Sale Of Electricity to Retailers',
            'description' => 'Selling electricity to retailers who distribute to end consumers.',
            'sale_type' => 'Electricity Supply to Retailers',
            'buyer_registration_type' => 'Unregistered',
            'rate' => '5%',
            'example_hs_code' => '0101.2100',
            'sro_schedule_no' => '1450(I)/2021'
        ],
        'SN014' => [
            'name' => 'Sale of Gas to CNG Stations',
            'description' => 'Natural gas sold to CNG filling stations with special tax treatment.',
            'sale_type' => 'Gas to CNG stations',
            'buyer_registration_type' => 'Unregistered',
            'rate' => '18%',
            'example_hs_code' => '0101.2100'
        ],
        'SN015' => [
            'name' => 'Sale of Mobile Phones',
            'description' => 'Sales of mobile handsets with standard sales tax and possible additional duties.',
            'sale_type' => 'Mobile Phones',
            'buyer_registration_type' => 'Unregistered',
            'rate' => '18%',
            'example_hs_code' => '0101.2100',
            'sro_schedule_no' => 'NINTH SCHEDULE'
        ],
        'SN016' => [
            'name' => 'Processing / Conversion of Goods',
            'description' => 'Services where raw materials are converted into finished products through processing.',
            'sale_type' => 'Processing/Conversion of Goods',
            'buyer_registration_type' => 'Unregistered',
            'rate' => '5%',
            'example_hs_code' => '0101.2100'
        ],
        'SN017' => [
            'name' => 'Sale of Goods Where FED Is Charged in ST Mode',
            'description' => 'Federal Excise Duty collected through the sales tax system.',
            'sale_type' => 'Goods (FED in ST Mode)',
            'buyer_registration_type' => 'Unregistered',
            'rate' => '8%',
            'example_hs_code' => '0101.2100'
        ],
        'SN018' => [
            'name' => 'Sale Of Services Where FED Is Charged in ST Mode',
            'description' => 'Services (advertisement, franchise, insurance) liable to FED but invoiced under sales tax framework.',
            'sale_type' => 'Services (FED in ST Mode)',
            'buyer_registration_type' => 'Unregistered',
            'rate' => '8%',
            'example_hs_code' => '0101.2100'
        ],
        'SN019' => [
            'name' => 'Sale of Services (as per ICT Ordinance)',
            'description' => 'IT services taxed under ICT ordinance with variations in rates or exemptions.',
            'sale_type' => 'Services',
            'buyer_registration_type' => 'Unregistered',
            'rate' => '5%',
            'example_hs_code' => '0101.2900',
            'sro_schedule_no' => 'ICTO TABLE I'
        ],
        'SN020' => [
            'name' => 'Sale of Electric Vehicles',
            'description' => 'Electric vehicles incentivized through reduced sales tax rates or exemptions.',
            'sale_type' => 'Electric Vehicle',
            'buyer_registration_type' => 'Unregistered',
            'rate' => '1%',
            'example_hs_code' => '0101.2900',
            'sro_schedule_no' => '6th Schd Table III'
        ],
        'SN021' => [
            'name' => 'Sale of Cement /Concrete Block',
            'description' => 'Cement and concrete blocks taxed at standard rate with strict regulation.',
            'sale_type' => 'Cement /Concrete Block',
            'buyer_registration_type' => 'Unregistered',
            'rate' => 'Rs.3',
            'example_hs_code' => '0101.2100'
        ],
        'SN022' => [
            'name' => 'Sale of Potassium Chlorate',
            'description' => 'Sensitive chemical subject to fixed tax per kilogram rather than value.',
            'sale_type' => 'Potassium Chlorate',
            'buyer_registration_type' => 'Unregistered',
            'rate' => '18% along with rupees 60 per kilogram',
            'example_hs_code' => '3104.2000',
            'sro_schedule_no' => 'EIGHTH SCHEDULE Table 1'
        ],
        'SN023' => [
            'name' => 'Sale of CNG',
            'description' => 'Compressed Natural Gas with regulated pricing and specific tax treatments.',
            'sale_type' => 'CNG Sales',
            'buyer_registration_type' => 'Unregistered',
            'rate' => 'Rs.200',
            'example_hs_code' => '0101.2100',
            'sro_schedule_no' => '581(1)/2024'
        ],
        'SN024' => [
            'name' => 'Sale Of Goods Listed in SRO 297(1)/2023',
            'description' => 'Specific goods subject to reduced, conditional, or fixed-rate taxation.',
            'sale_type' => 'Goods as per SRO.297(|)/2023',
            'buyer_registration_type' => 'Unregistered',
            'rate' => '25%',
            'example_hs_code' => '0101.2100',
            'sro_schedule_no' => '297(I)/2023-Table-I'
        ],
        'SN025' => [
            'name' => 'Drugs Sold at Fixed ST Rate Under Serial 81 Of Eighth Schedule Table 1',
            'description' => 'Pharmaceutical products taxed at fixed sales tax rate to make medicines affordable.',
            'sale_type' => 'Non-Adjustable Supplies',
            'buyer_registration_type' => 'Unregistered',
            'rate' => '0%',
            'example_hs_code' => '0101.2100',
            'sro_schedule_no' => 'EIGHTH SCHEDULE Table 1'
        ],
        'SN026' => [
            'name' => 'Sale Of Goods at Standard Rate to End Consumers by Retailers',
            'description' => 'Retailers selling taxable goods directly to end consumers at standard rate.',
            'sale_type' => 'Goods at standard rate (default)',
            'buyer_registration_type' => 'Registered',
            'rate' => '18%',
            'example_hs_code' => '0101.2100'
        ],
        'SN027' => [
            'name' => 'Sale Of 3rd Schedule Goods to End Consumers by Retailers',
            'description' => 'Retailers selling 3rd Schedule goods based on maximum retail price (MRP).',
            'sale_type' => '3rd Schedule Goods',
            'buyer_registration_type' => 'Registered',
            'rate' => '18%',
            'example_hs_code' => '0101.2100'
        ],
        'SN028' => [
            'name' => 'Sale of Goods at Reduced Rate to End Consumers by Retailers',
            'description' => 'Essential goods at reduced tax rate to keep vital products affordable.',
            'sale_type' => 'Goods at Reduced Rate',
            'buyer_registration_type' => 'Registered',
            'rate' => '1%',
            'example_hs_code' => '0101.2100',
            'sro_schedule_no' => 'EIGHTH SCHEDULE Table 1'
        ]
    ];

    public static function getScenario(string $scenarioId): ?array
    {
        return self::SCENARIOS[$scenarioId] ?? null;
    }

    public static function getAllScenarios(): array
    {
        return self::SCENARIOS;
    }

    public static function getScenariosByBusinessActivity(string $activity, string $sector = null): array
    {
        // Based on the business activity mapping from the original document
        $activityScenarios = [
            'manufacturer' => [
                'all_sectors' => ['SN001', 'SN002', 'SN005', 'SN006', 'SN007', 'SN015', 'SN016', 'SN017', 'SN021', 'SN022', 'SN024'],
                'steel' => ['SN003', 'SN004', 'SN011'],
                'fmcg' => ['SN001', 'SN002', 'SN005', 'SN006', 'SN007', 'SN015', 'SN016', 'SN017', 'SN021', 'SN022', 'SN024', 'SN008'],
                'textile' => ['SN001', 'SN002', 'SN005', 'SN006', 'SN007', 'SN015', 'SN016', 'SN017', 'SN021', 'SN022', 'SN024', 'SN009'],
                'telecom' => ['SN001', 'SN002', 'SN005', 'SN006', 'SN007', 'SN015', 'SN016', 'SN017', 'SN021', 'SN022', 'SN024', 'SN010'],
                'petroleum' => ['SN001', 'SN002', 'SN005', 'SN006', 'SN007', 'SN015', 'SN016', 'SN017', 'SN021', 'SN022', 'SN024', 'SN012'],
                'automobile' => ['SN001', 'SN002', 'SN005', 'SN006', 'SN007', 'SN015', 'SN016', 'SN017', 'SN021', 'SN022', 'SN024', 'SN020'],
                'pharmaceuticals' => ['SN001', 'SN002', 'SN005', 'SN006', 'SN007', 'SN015', 'SN016', 'SN017', 'SN021', 'SN022', 'SN024', 'SN025']
            ],
            'retailer' => [
                'all_sectors' => ['SN026', 'SN027', 'SN028'],
                'steel' => ['SN003', 'SN004', 'SN011'],
                'fmcg' => ['SN026', 'SN027', 'SN028', 'SN008'],
                'textile' => ['SN009', 'SN026', 'SN027', 'SN028', 'SN008'],
                'pharmaceuticals' => ['SN025', 'SN026', 'SN027', 'SN028', 'SN008']
            ],
            'service_provider' => [
                'all_sectors' => ['SN018', 'SN019'],
                'telecom' => ['SN010', 'SN018', 'SN019'],
                'steel' => ['SN003', 'SN004', 'SN011', 'SN018', 'SN019']
            ]
        ];

        $scenarios = $activityScenarios[$activity][$sector] ?? $activityScenarios[$activity]['all_sectors'] ?? [];
        
        return array_filter(self::SCENARIOS, function($key) use ($scenarios) {
            return in_array($key, $scenarios);
        }, ARRAY_FILTER_USE_KEY);
    }
}

// File: src/Builders/ScenarioInvoiceBuilder.php
namespace Fbr\DigitalInvoicing\Builders;

use Fbr\DigitalInvoicing\Constants\FbrScenarios;

class ScenarioInvoiceBuilder extends InvoiceBuilder
{
    private string $scenarioId;

    public function forScenario(string $scenarioId): self
    {
        $scenario = FbrScenarios::getScenario($scenarioId);
        
        if (!$scenario) {
            throw new \InvalidArgumentException("Invalid scenario ID: {$scenarioId}");
        }

        $this->scenarioId = $scenarioId;
        $this->setScenarioId($scenarioId);
        
        return $this;
    }

    public function addScenarioItem(array $overrides = []): self
    {
        $scenario = FbrScenarios::getScenario($this->scenarioId);
        
        $defaultItem = [
            'hsCode' => $scenario['example_hs_code'],
            'productDescription' => 'Test Product',
            'rate' => $scenario['rate'],
            'uoM' => $this->getDefaultUom($scenario),
            'quantity' => $this->getDefaultQuantity($scenario),
            'totalValues' => 0,
            'valueSalesExcludingST' => $this->getDefaultSalesValue($scenario),
            'fixedNotifiedValueOrRetailPrice' => $this->getDefaultFixedPrice($scenario),
            'salesTaxApplicable' => $this->calculateSalesTax($scenario),
            'salesTaxWithheldAtSource' => 0,
            'extraTax' => 0,
            'furtherTax' => 0,
            'sroScheduleNo' => $scenario['sro_schedule_no'] ?? '',
            'fedPayable' => 0,
            'discount' => 0,
            'saleType' => $scenario['sale_type'],
            'sroItemSerialNo' => $this->getDefaultSroItemSerialNo($scenario)
        ];

        $item = array_merge($defaultItem, $overrides);
        $this->addItem($item);
        
        return $this;
    }

    private function getDefaultUom(array $scenario): string
    {
        return match ($this->scenarioId) {
            'SN003', 'SN004', 'SN011' => 'MT',
            'SN022' => 'KG',
            default => 'Numbers, pieces, units'
        };
    }

    private function getDefaultQuantity(array $scenario): float
    {
        return match ($this->scenarioId) {
            'SN001', 'SN002' => 400,
            'SN003', 'SN004', 'SN011', 'SN016', 'SN017', 'SN022', 'SN025' => 1,
            'SN007', 'SN008' => 100,
            'SN009' => 0, // Special case for cotton ginners
            'SN010' => 1000,
            'SN012', 'SN013', 'SN014', 'SN015', 'SN018', 'SN026' => 123,
            'SN019' => 1,
            'SN020' => 122,
            'SN021' => 12,
            'SN023', 'SN024', 'SN027' => 123,
            'SN028' => 0, // Special case for reduced rate retailers
            default => 1
        };
    }

    private function getDefaultSalesValue(array $scenario): float
    {
        return match ($this->scenarioId) {
            'SN003' => 205000,
            'SN004' => 175000,
            'SN005', 'SN006', 'SN001', 'SN002', 'SN014' => 1000,
            'SN007' => 100,
            'SN008', 'SN027', 'SN028' => 0, // 3rd Schedule uses fixed price
            'SN009' => 2500,
            'SN010', 'SN012', 'SN016', 'SN017', 'SN019', 'SN022', 'SN025' => 100,
            'SN011' => 205000,
            'SN013' => 1000,
            'SN015' => 1234,
            'SN018' => 1000,
            'SN020' => 1000,
            'SN021' => 123,
            'SN023' => 234,
            'SN024', 'SN026' => 1000,
            default => 1000
        };
    }

    private function getDefaultFixedPrice(array $scenario): float
    {
        return match ($this->scenarioId) {
            'SN008' => 1000, // 3rd Schedule uses fixed price
            'SN027', 'SN028' => 100, // Retailers selling 3rd schedule
            default => 0
        };
    }

    private function calculateSalesTax(array $scenario): float
    {
        $rate = $scenario['rate'];
        $salesValue = $this->getDefaultSalesValue($scenario);
        $fixedPrice = $this->getDefaultFixedPrice($scenario);
        
        // For 3rd schedule, calculate on fixed price
        $baseValue = $fixedPrice > 0 ? $fixedPrice : $salesValue;
        
        return match (true) {
            str_contains($rate, '%') => $baseValue * (floatval($rate) / 100),
            $rate === 'Exempt' => 0,
            str_contains($rate, 'Rs.') => $this->calculateFixedRateTax($rate),
            str_contains($rate, 'along with') => $this->calculateComplexRateTax($rate, $baseValue),
            default => 0
        };
    }

    private function calculateFixedRateTax(string $rate): float
    {
        return match ($this->scenarioId) {
            'SN021' => 36, // Rs.3 per unit * 12 units
            'SN023' => 24600, // Rs.200 per unit * 123 units
            default => 0
        };
    }

    private function calculateComplexRateTax(string $rate, float $baseValue): float
    {
        // For potassium chlorate: 18% along with rupees 60 per kilogram
        if ($this->scenarioId === 'SN022') {
            $percentTax = $baseValue * 0.18; // 18%
            $fixedTax = 60 * 1; // 60 per kg * 1 kg
            return $percentTax + $fixedTax;
        }
        
        return 0;
    }

    private function getDefaultSroItemSerialNo(array $scenario): string
    {
        return match ($this->scenarioId) {
            'SN005' => '82',
            'SN006' => '100',
            'SN007' => '1',
            'SN012', 'SN013' => '4',
            'SN015' => '1(A)',
            'SN019' => '1(ii)(ii)(a)',
            'SN020' => '20',
            'SN022' => '56',
            'SN023' => 'Region-I',
            'SN024' => '12',
            'SN025' => '81',
            'SN028' => '70',
            default => ''
        };
    }
}

// File: src/Services/ScenarioTestingService.php
namespace Fbr\DigitalInvoicing\Services;

use Fbr\DigitalInvoicing\Builders\ScenarioInvoiceBuilder;
use Fbr\DigitalInvoicing\Constants\FbrScenarios;
use Fbr\DigitalInvoicing\Services\FbrDigitalInvoicingService;

class ScenarioTestingService
{
    private FbrDigitalInvoicingService $invoicingService;

    public function __construct(FbrDigitalInvoicingService $invoicingService)
    {
        $this->invoicingService = $invoicingService;
    }

    /**
     * Test a specific scenario
     */
    public function testScenario(string $scenarioId, array $customData = []): array
    {
        $scenario = FbrScenarios::getScenario($scenarioId);
        
        if (!$scenario) {
            throw new \InvalidArgumentException("Invalid scenario ID: {$scenarioId}");
        }

        $invoice = $this->buildScenarioInvoice($scenarioId, $customData);
        
        return $this->invoicingService->postInvoiceData($invoice);
    }

    /**
     * Validate a specific scenario without submitting
     */
    public function validateScenario(string $scenarioId, array $customData = []): array
    {
        $scenario = FbrScenarios::getScenario($scenarioId);
        
        if (!$scenario) {
            throw new \InvalidArgumentException("Invalid scenario ID: {$scenarioId}");
        }

        $invoice = $this->buildScenarioInvoice($scenarioId, $customData);
        
        return $this->invoicingService->validateInvoiceData($invoice);
    }

    /**
     * Test all scenarios for a business activity
     */
    public function testBusinessActivityScenarios(string $activity, string $sector = null): array
    {
        $scenarios = FbrScenarios::getScenariosByBusinessActivity($activity, $sector);
        $results = [];

        foreach ($scenarios as $scenarioId => $scenarioData) {
            try {
                $result = $this->testScenario($scenarioId);
                $results[$scenarioId] = [
                    'status' => 'success',
                    'scenario_name' => $scenarioData['name'],
                    'response' => $result
                ];
            } catch (\Exception $e) {
                $results[$scenarioId] = [
                    'status' => 'error',
                    'scenario_name' => $scenarioData['name'],
                    'error' => $e->getMessage()
                ];
            }
        }

        return $results;
    }

    /**
     * Build invoice for a specific scenario
     */
    private function buildScenarioInvoice(string $scenarioId, array $customData = []): array
    {
        $scenario = FbrScenarios::getScenario($scenarioId);
        
        $defaultData = [
            'seller_ntn_cnic' => '8885801',
            'seller_business_name' => 'Company 8',
            'seller_province' => 'Sindh',
            'seller_address' => 'Karachi',
            'buyer_ntn_cnic' => $this->getDefaultBuyerNtn($scenarioId),
            'buyer_business_name' => 'FERTILIZER MANUFAC IRS NEW',
            'buyer_province' => 'Sindh',
            'buyer_address' => 'Karachi',
            'invoice_date' => now()->format('Y-m-d')
        ];

        $invoiceData = array_merge($defaultData, $customData);

        $invoice = (new ScenarioInvoiceBuilder())
            ->setInvoiceType('Sale Invoice')
            ->setInvoiceDate($invoiceData['invoice_date'])
            ->setSeller(
                $invoiceData['seller_ntn_cnic'],
                $invoiceData['seller_business_name'],
                $invoiceData['seller_province'],
                $invoiceData['seller_address']
            )
            ->setBuyer(
                $invoiceData['buyer_ntn_cnic'],
                $invoiceData['buyer_business_name'],
                $invoiceData['buyer_province'],
                $invoiceData['buyer_address'],
                $scenario['buyer_registration_type']
            )
            ->setInvoiceRefNo($customData['invoice_ref_no'] ?? '')
            ->forScenario($scenarioId)
            ->addScenarioItem($customData['item_overrides'] ?? [])
            ->build();

        return $invoice;
    }

    private function getDefaultBuyerNtn(string $scenarioId): string
    {
        return match ($scenarioId) {
            'SN001', 'SN006', 'SN009' => '2046004', // Registered buyers
            'SN003', 'SN004', 'SN007', 'SN008', 'SN011' => '3710505701479',
            'SN017' => '7000009',
            'SN018' => '1000000000056',
            'SN026', 'SN027' => '7000008', // Retailers
            default => '1000000000000' // Unregistered default
        };
    }

    /**
     * Get scenario examples for documentation
     */
    public function getScenarioExamples(): array
    {
        $examples = [];
        
        foreach (FbrScenarios::SCENARIOS as $scenarioId => $scenario) {
            $examples[$scenarioId] = [
                'scenario' => $scenario,
                'sample_invoice' => $this->buildScenarioInvoice($scenarioId)
            ];
        }