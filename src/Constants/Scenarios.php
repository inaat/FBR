<?php

namespace Fbr\DigitalInvoicing\Constants;

class Scenarios
{
    /**
     * Official FBR sandbox testing scenarios with exact test data
     * Based on "DI Scenarios Description for Sandbox Testing" v1.11
     */
    public const SCENARIOS = [
        'SN001' => [
            'name' => 'Sale of Standard Rate Goods to Registered Buyers',
            'description' => 'Sale of goods subject to the standard sales tax rate made to sales tax registered buyers. These buyers can usually claim input tax credits.',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '2046004',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Registered',
                'item' => [
                    'hsCode' => '0101.2100',
                    'productDescription' => 'test',
                    'rate' => '18%',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 400,
                    'valueSalesExcludingST' => 1000,
                    'salesTaxApplicable' => 180,
                    'saleType' => 'Goods at standard rate (default)'
                ]
            ]
        ],
        'SN002' => [
            'name' => 'Sale of Standard Rate Goods to Unregistered Buyers',
            'description' => 'When goods subject to the standard sales tax rate are sold to buyers who are not registered for sales tax (usually individual consumers or small businesses not registered for tax).',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '1234567',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Unregistered',
                'item' => [
                    'hsCode' => '0101.2100',
                    'productDescription' => 'test',
                    'rate' => '18%',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 400,
                    'valueSalesExcludingST' => 1000,
                    'salesTaxApplicable' => 180,
                    'saleType' => 'Goods at standard rate (default)'
                ]
            ]
        ],
        'SN003' => [
            'name' => 'Sale of Steel (Melted and Re-Rolled) (Billets, Ingots and Long Bars)',
            'description' => 'The steel sector is governed by strict traceability and sector-specific rules. These goods are commonly traded by re-rollers and manufacturers.',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'Company 7',
                'buyerNTNCNIC' => '3710505701479',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Unregistered',
                'item' => [
                    'hsCode' => '7214.1010',
                    'productDescription' => '',
                    'rate' => '18%',
                    'uoM' => 'MT',
                    'quantity' => 1,
                    'valueSalesExcludingST' => 205000.00,
                    'salesTaxApplicable' => 36900,
                    'saleType' => 'Steel melting and re-rolling'
                ]
            ]
        ],
        'SN004' => [
            'name' => 'Sale of Steel Scrap by Ship Breakers',
            'description' => 'Ship breakers dismantle old ships and recover scrap steel, which they then sell. This scrap steel is often treated separately for tax purposes.',
            'sample_data' => [
                'sellerNTNCNIC' => '4130276175937',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '3710505701479',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Unregistered',
                'invoiceRefNo' => 'SI-20250421-001',
                'item' => [
                    'hsCode' => '7204.1010',
                    'productDescription' => '',
                    'rate' => '18%',
                    'uoM' => 'MT',
                    'quantity' => 1,
                    'valueSalesExcludingST' => 175000,
                    'salesTaxApplicable' => 31500,
                    'saleType' => 'Ship breaking'
                ]
            ]
        ],
        'SN005' => [
            'name' => 'Sales of Reduced Rate Goods (Eighth Schedule)',
            'description' => 'Certain goods are taxed at a reduced sales tax rate (lower than the standard rate) to encourage affordability or protect consumers.',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'B2',
                'buyerNTNCNIC' => '1000000000000',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Unregistered',
                'item' => [
                    'hsCode' => '0102.2930',
                    'productDescription' => 'product Description41',
                    'rate' => '1%',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 1.0000,
                    'valueSalesExcludingST' => 1000.00,
                    'salesTaxApplicable' => 10,
                    'salesTaxWithheldAtSource' => 50.23,
                    'furtherTax' => 120.00,
                    'sroScheduleNo' => 'EIGHTH SCHEDULE Table 1',
                    'fedPayable' => 50.36,
                    'discount' => 56.36,
                    'saleType' => 'Goods at Reduced Rate',
                    'sroItemSerialNo' => '82'
                ]
            ]
        ],
        'SN006' => [
            'name' => 'Sale of Exempt Goods (Sixth Schedule)',
            'description' => 'Goods listed in the Sixth Schedule are exempt from sales tax, meaning sellers do not charge sales tax on these goods.',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '2046004',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Registered',
                'invoiceRefNo' => 'SI-20250515-001',
                'item' => [
                    'hsCode' => '0102.2930',
                    'productDescription' => 'product Description41',
                    'rate' => 'Exempt',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 1.0000,
                    'valueSalesExcludingST' => 10,
                    'salesTaxApplicable' => 0,
                    'salesTaxWithheldAtSource' => 50.23,
                    'furtherTax' => 120.00,
                    'sroScheduleNo' => '6th Schd Table I',
                    'fedPayable' => 50.36,
                    'discount' => 56.36,
                    'saleType' => 'Exempt goods',
                    'sroItemSerialNo' => '100'
                ]
            ]
        ],
        'SN007' => [
            'name' => 'Sale Of Zero-Rated Goods (Fifth Schedule)',
            'description' => 'Zero-rated goods are those on which sales tax is charged at 0%. While the seller does not charge sales tax to the buyer, the seller can claim input tax credits.',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'Company 7',
                'buyerNTNCNIC' => '3710505701479',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Unregistered',
                'invoiceRefNo' => '0',
                'item' => [
                    'hsCode' => '0101.2100',
                    'productDescription' => 'test',
                    'rate' => '0%',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 100,
                    'valueSalesExcludingST' => 100,
                    'salesTaxApplicable' => 0,
                    'sroScheduleNo' => '327(I)/2008',
                    'saleType' => 'Goods at zero-rate',
                    'sroItemSerialNo' => '1'
                ]
            ]
        ],
        'SN008' => [
            'name' => 'Sale of 3rd Schedule Goods',
            'description' => 'Items listed under the Third Schedule are subject to sales tax on the basis of their printed retail price rather than the transaction value.',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'Company 7',
                'buyerNTNCNIC' => '3710505701479',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Unregistered',
                'invoiceRefNo' => '0',
                'item' => [
                    'hsCode' => '0101.2100',
                    'productDescription' => 'test',
                    'rate' => '18%',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 100,
                    'totalValues' => 145,
                    'valueSalesExcludingST' => 0,
                    'fixedNotifiedValueOrRetailPrice' => 1000,
                    'salesTaxApplicable' => 180,
                    'saleType' => '3rd Schedule Goods'
                ]
            ]
        ],
        'SN009' => [
            'name' => 'Purchase From Registered Cotton Ginners',
            'description' => 'Purchases from registered cotton ginners, subject to specific rules under cotton trade taxation. May involve reverse charge or input tax mechanisms.',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '2046004',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Registered',
                'item' => [
                    'hsCode' => '0101.2100',
                    'productDescription' => 'test',
                    'rate' => '18%',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 0,
                    'totalValues' => 2500,
                    'valueSalesExcludingST' => 2500,
                    'salesTaxApplicable' => 450,
                    'saleType' => 'Cotton ginners'
                ]
            ]
        ],
        'SN010' => [
            'name' => 'Sale Of Telecom Services by Mobile Operators',
            'description' => 'Mobile operators provide telecom services (calls, data, SMS). These services are typically taxed under specific rules separate from goods.',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '1000000000000',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Unregistered',
                'invoiceRefNo' => 'SI-20250515-001',
                'item' => [
                    'hsCode' => '0101.2100',
                    'productDescription' => 'test',
                    'rate' => '17%',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 1000,
                    'valueSalesExcludingST' => 100,
                    'salesTaxApplicable' => 17,
                    'saleType' => 'Telecommunication services'
                ]
            ]
        ],
        'SN011' => [
            'name' => 'Sale of Steel through Toll Manufacturing (Billets, Ingots and Long Bars)',
            'description' => 'Toll manufacturing involves a third-party processing raw steel into finished billets, ingots, or long bars on behalf of another business.',
            'sample_data' => [
                'sellerNTNCNIC' => '4130276175937',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '3710505701479',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Unregistered',
                'item' => [
                    'hsCode' => '7214.9990',
                    'productDescription' => '',
                    'rate' => '18%',
                    'uoM' => 'MT',
                    'quantity' => 1,
                    'valueSalesExcludingST' => 205000,
                    'salesTaxApplicable' => 36900,
                    'saleType' => 'Toll Manufacturing'
                ]
            ]
        ],
        'SN012' => [
            'name' => 'Sale Of Petroleum Products',
            'description' => 'Petroleum products like petrol, diesel, or lubricants often have distinct sales tax rates or are subject to federal excise duties.',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '1000000000000',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Unregistered',
                'invoiceRefNo' => 'SI-20250515-001',
                'item' => [
                    'hsCode' => '0101.2100',
                    'productDescription' => 'TEST',
                    'rate' => '1.43%',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 123,
                    'totalValues' => 132,
                    'valueSalesExcludingST' => 100,
                    'salesTaxApplicable' => 1.43,
                    'salesTaxWithheldAtSource' => 2,
                    'sroScheduleNo' => '1450(I)/2021',
                    'saleType' => 'Petroleum Products',
                    'sroItemSerialNo' => '4'
                ]
            ]
        ],
        'SN013' => [
            'name' => 'Sale Of Electricity to Retailers',
            'description' => 'Selling electricity to retailers who distribute to end consumers can have unique tax implications.',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '1000000000000',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Unregistered',
                'invoiceRefNo' => 'SI-20250515-001',
                'item' => [
                    'hsCode' => '0101.2100',
                    'productDescription' => 'TEST',
                    'rate' => '5%',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 123,
                    'totalValues' => 212,
                    'valueSalesExcludingST' => 1000,
                    'salesTaxApplicable' => 50,
                    'salesTaxWithheldAtSource' => 11,
                    'sroScheduleNo' => '1450(I)/2021',
                    'saleType' => 'Electricity Supply to Retailers',
                    'sroItemSerialNo' => '4'
                ]
            ]
        ],
        'SN014' => [
            'name' => 'Sale of Gas to CNG Stations',
            'description' => 'Natural gas sold to CNG (Compressed Natural Gas) filling stations may have special tax treatment to promote cleaner fuels.',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '1000000000000',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Unregistered',
                'invoiceRefNo' => 'SI-20250515-001',
                'item' => [
                    'hsCode' => '0101.2100',
                    'productDescription' => 'TEST',
                    'rate' => '18%',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 123,
                    'valueSalesExcludingST' => 1000,
                    'salesTaxApplicable' => 180,
                    'saleType' => 'Gas to CNG stations'
                ]
            ]
        ],
        'SN015' => [
            'name' => 'Sale of Mobile Phones',
            'description' => 'Sales of mobile handsets often attract standard sales tax but might be subject to additional duties or regulatory charges.',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '1000000000000',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Unregistered',
                'invoiceRefNo' => 'SI-20250515-001',
                'item' => [
                    'hsCode' => '0101.2100',
                    'productDescription' => 'TEST',
                    'rate' => '18%',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 123,
                    'valueSalesExcludingST' => 1234,
                    'salesTaxApplicable' => 222.12,
                    'sroScheduleNo' => 'NINTH SCHEDULE',
                    'saleType' => 'Mobile Phones',
                    'sroItemSerialNo' => '1(A)'
                ]
            ]
        ],
        'SN016' => [
            'name' => 'Processing / Conversion of Goods',
            'description' => 'This refers to services where raw materials or semi-finished goods are converted into finished products through manufacturing or processing.',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '1000000000078',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Unregistered',
                'item' => [
                    'hsCode' => '0101.2100',
                    'productDescription' => 'test',
                    'rate' => '5%',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 1,
                    'valueSalesExcludingST' => 100,
                    'salesTaxApplicable' => 5,
                    'saleType' => 'Processing/Conversion of Goods'
                ]
            ]
        ],
        'SN017' => [
            'name' => 'Sale of Goods Where FED Is Charged in ST Mode',
            'description' => 'Federal Excise Duty (FED) may be charged alongside sales tax, but in some cases, FED is collected through the sales tax system ("ST mode").',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '7000009',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Unregistered',
                'item' => [
                    'hsCode' => '0101.2100',
                    'productDescription' => 'TEST',
                    'rate' => '8%',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 1,
                    'valueSalesExcludingST' => 100,
                    'salesTaxApplicable' => 8,
                    'saleType' => 'Goods (FED in ST Mode)'
                ]
            ]
        ],
        'SN018' => [
            'name' => 'Sale Of Services Where FED Is Charged in ST Mode',
            'description' => 'Certain services (e.g., advertisement, franchise, insurance) are liable to FED but invoiced under the sales tax framework.',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '1000000000056',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Unregistered',
                'invoiceRefNo' => 'SI-20250421-001',
                'item' => [
                    'hsCode' => '0101.2100',
                    'productDescription' => 'TEST',
                    'rate' => '8%',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 20,
                    'valueSalesExcludingST' => 1000,
                    'salesTaxApplicable' => 80,
                    'saleType' => 'Services (FED in ST Mode)'
                ]
            ]
        ],
        'SN019' => [
            'name' => 'Sale of Services (as per ICT Ordinance)',
            'description' => 'Businesses providing services such as consultancy, software development, and IT solutions in ICT are taxed under a distinct ordinance.',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '1000000000000',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Unregistered',
                'invoiceRefNo' => 'SI-20250421-001',
                'item' => [
                    'hsCode' => '0101.2900',
                    'productDescription' => 'TEST',
                    'rate' => '5%',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 1,
                    'valueSalesExcludingST' => 100,
                    'salesTaxApplicable' => 5,
                    'sroScheduleNo' => 'ICTO TABLE I',
                    'saleType' => 'Services',
                    'sroItemSerialNo' => '1(ii)(ii)(a)'
                ]
            ]
        ],
        'SN020' => [
            'name' => 'Sale of Electric Vehicles',
            'description' => 'Electric vehicles may be incentivized through reduced sales tax rates or exemptions to encourage environmentally friendly transportation.',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '1000000000000',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Unregistered',
                'invoiceRefNo' => 'SI-20250421-001',
                'item' => [
                    'hsCode' => '0101.2900',
                    'productDescription' => 'TEST',
                    'rate' => '1%',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 122,
                    'valueSalesExcludingST' => 1000,
                    'salesTaxApplicable' => 10,
                    'sroScheduleNo' => '6th Schd Table III',
                    'saleType' => 'Electric Vehicle',
                    'sroItemSerialNo' => '20'
                ]
            ]
        ],
        'SN021' => [
            'name' => 'Sale of Cement /Concrete Block',
            'description' => 'Cement and concrete blocks are taxed at the standard rate and are subject to strict regulation due to their environmental impact.',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '1000000000000',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Unregistered',
                'invoiceRefNo' => 'SI-20250421-001',
                'item' => [
                    'hsCode' => '0101.2100',
                    'productDescription' => 'TEST',
                    'rate' => 'Rs.3',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 12,
                    'valueSalesExcludingST' => 123,
                    'salesTaxApplicable' => 36,
                    'saleType' => 'Cement /Concrete Block'
                ]
            ]
        ],
        'SN022' => [
            'name' => 'Sale of Potassium Chlorate',
            'description' => 'Potassium chlorate is a sensitive chemical used primarily in matchstick manufacturing and regulated under special rules.',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '1000000000000',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Unregistered',
                'invoiceRefNo' => 'SI-20250421-001',
                'item' => [
                    'hsCode' => '3104.2000',
                    'productDescription' => 'TEST',
                    'rate' => '18% along with rupees 60 per kilogram',
                    'uoM' => 'KG',
                    'quantity' => 1,
                    'valueSalesExcludingST' => 100,
                    'salesTaxApplicable' => 78,
                    'sroScheduleNo' => 'EIGHTH SCHEDULE Table 1',
                    'saleType' => 'Potassium Chlorate',
                    'sroItemSerialNo' => '56'
                ]
            ]
        ],
        'SN023' => [
            'name' => 'Sale of CNG',
            'description' => 'Sales of Compressed Natural Gas involve regulated pricing structures and specific tax treatments that may include both FED and sales tax.',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '1000000000000',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Unregistered',
                'invoiceRefNo' => 'SI-20250421-001',
                'item' => [
                    'hsCode' => '0101.2100',
                    'productDescription' => 'TEST',
                    'rate' => 'Rs.200',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 123,
                    'valueSalesExcludingST' => 234,
                    'salesTaxApplicable' => 24600,
                    'sroScheduleNo' => '581(1)/2024',
                    'saleType' => 'CNG Sales',
                    'sroItemSerialNo' => 'Region-I'
                ]
            ]
        ],
        'SN024' => [
            'name' => 'Sale Of Goods Listed in SRO 297(1)/2023',
            'description' => 'This SRO notifies specific goods subject to reduced, conditional, or fixed-rate taxation. These may include solar equipment, medical devices, or energy-efficient appliances.',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '1000000000000',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Unregistered',
                'invoiceRefNo' => 'SI-20250421-001',
                'item' => [
                    'hsCode' => '0101.2100',
                    'productDescription' => 'TEST',
                    'rate' => '25%',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 123,
                    'valueSalesExcludingST' => 1000,
                    'salesTaxApplicable' => 250,
                    'sroScheduleNo' => '297(I)/2023-Table-I',
                    'saleType' => 'Goods as per SRO.297(|)/2023',
                    'sroItemSerialNo' => '12'
                ]
            ]
        ],
        'SN025' => [
            'name' => 'Drugs Sold at Fixed ST Rate Under Serial 81 Of Eighth Schedule Table 1',
            'description' => 'Certain pharmaceutical products are taxed at a fixed sales tax rate under serial 81 of the Eighth Schedule.',
            'sample_data' => [
                'sellerNTNCNIC' => '8885801',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '1000000000078',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Unregistered',
                'item' => [
                    'hsCode' => '0101.2100',
                    'productDescription' => 'TEST',
                    'rate' => '0%',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 1,
                    'valueSalesExcludingST' => 100,
                    'salesTaxApplicable' => 0,
                    'sroScheduleNo' => 'EIGHTH SCHEDULE Table 1',
                    'saleType' => 'Non-Adjustable Supplies',
                    'sroItemSerialNo' => '81'
                ]
            ]
        ],
        'SN026' => [
            'name' => 'Sale Of Goods at Standard Rate to End Consumers by Retailers',
            'description' => 'Retailers selling taxable goods directly to end consumers must apply the standard rate of sales tax at the point of sale.',
            'sample_data' => [
                'sellerNTNCNIC' => '7000008',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '1000000000078',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Registered',
                'invoiceRefNo' => 'SI-20250421-001',
                'item' => [
                    'hsCode' => '0101.2100',
                    'productDescription' => 'TEST',
                    'rate' => '18%',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 123,
                    'valueSalesExcludingST' => 1000,
                    'salesTaxApplicable' => 180,
                    'saleType' => 'Goods at standard rate (default)'
                ]
            ]
        ],
        'SN027' => [
            'name' => 'Sale Of 3rd Schedule Goods to End Consumers by Retailers',
            'description' => 'Retailers selling goods under the 3rd Schedule, such as branded FMCGs, must charge and report sales tax based on the maximum retail price (MRP).',
            'sample_data' => [
                'sellerNTNCNIC' => '7000008',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '7000006',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Registered',
                'item' => [
                    'hsCode' => '0101.2100',
                    'productDescription' => 'test',
                    'rate' => '18%',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 1,
                    'valueSalesExcludingST' => 0,
                    'fixedNotifiedValueOrRetailPrice' => 100,
                    'salesTaxApplicable' => 18,
                    'saleType' => '3rd Schedule Goods'
                ]
            ]
        ],
        'SN028' => [
            'name' => 'Sale of Goods at Reduced Rate to End Consumers by Retailers',
            'description' => 'Certain essential goods, such as baby milk and books, are subject to a reduced tax rate lower than the standard 18% when sold to end consumers.',
            'sample_data' => [
                'sellerNTNCNIC' => '7000008',
                'sellerBusinessName' => 'Company 8',
                'buyerNTNCNIC' => '1000000000000',
                'buyerBusinessName' => 'FERTILIZER MANUFAC IRS NEW',
                'buyerRegistrationType' => 'Registered',
                'item' => [
                    'hsCode' => '0101.2100',
                    'productDescription' => 'TEST',
                    'rate' => '1%',
                    'uoM' => 'Numbers, pieces, units',
                    'quantity' => 0,
                    'valueSalesExcludingST' => 0,
                    'fixedNotifiedValueOrRetailPrice' => 100,
                    'salesTaxApplicable' => 0,
                    'sroScheduleNo' => 'EIGHTH SCHEDULE Table 1',
                    'saleType' => 'Goods at Reduced Rate',
                    'sroItemSerialNo' => '70'
                ]
            ]
        ]
    ];

    /**
     * Get scenario description by ID
     */
    public static function getScenarioDescription(string $scenarioId): string
    {
        return self::SCENARIOS[$scenarioId]['description'] ?? 'Unknown scenario';
    }

    /**
     * Get scenario details by ID
     */
    public static function getScenario(string $scenarioId): ?array
    {
        return self::SCENARIOS[$scenarioId] ?? null;
    }

    /**
     * Get scenario sample data for testing
     */
    public static function getScenarioSampleData(string $scenarioId): ?array
    {
        return self::SCENARIOS[$scenarioId]['sample_data'] ?? null;
    }

    /**
     * Get all scenarios
     */
    public static function getAllScenarios(): array
    {
        return self::SCENARIOS;
    }

    /**
     * Get simple scenario names for quick reference
     */
    public static function getSimpleScenarios(): array
    {
        $simple = [];
        foreach (self::SCENARIOS as $id => $scenario) {
            $simple[$id] = $scenario['name'];
        }
        return $simple;
    }

    /**
     * Get scenarios by business type
     */
    public static function getScenariosByBusinessType(string $businessType): array
    {
        $businessScenarios = [
            'manufacturer' => ['SN001', 'SN002', 'SN003', 'SN004', 'SN005', 'SN006', 'SN007', 'SN008', 'SN009', 'SN011', 'SN015', 'SN016', 'SN017', 'SN020', 'SN021', 'SN022', 'SN024', 'SN025'],
            'retailer' => ['SN026', 'SN027', 'SN028'],
            'service_provider' => ['SN010', 'SN016', 'SN018', 'SN019'],
            'energy_sector' => ['SN012', 'SN013', 'SN014', 'SN023'],
            'steel_sector' => ['SN003', 'SN004', 'SN011'],
            'telecom' => ['SN010'],
            'pharmaceutical' => ['SN025'],
            'automotive' => ['SN020'],
            'construction' => ['SN021'],
            'chemical' => ['SN022']
        ];

        $scenarios = $businessScenarios[$businessType] ?? [];
        
        return array_filter(self::SCENARIOS, function($key) use ($scenarios) {
            return in_array($key, $scenarios);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Generate complete invoice data for a scenario
     */
    public static function generateScenarioInvoice(string $scenarioId, array $overrides = []): array
    {
        $scenario = self::getScenario($scenarioId);
        if (!$scenario) {
            throw new \InvalidArgumentException("Invalid scenario ID: {$scenarioId}");
        }

        $sampleData = $scenario['sample_data'];
        $item = $sampleData['item'];

        // Build complete invoice structure
        $invoice = [
            'invoiceType' => 'Sale Invoice',
            'invoiceDate' => date('Y-m-d'),
            'sellerNTNCNIC' => $sampleData['sellerNTNCNIC'],
            'sellerBusinessName' => $sampleData['sellerBusinessName'],
            'sellerProvince' => 'Sindh',
            'sellerAddress' => 'Karachi',
            'buyerNTNCNIC' => $sampleData['buyerNTNCNIC'],
            'buyerBusinessName' => $sampleData['buyerBusinessName'],
            'buyerProvince' => 'Sindh',
            'buyerAddress' => 'Karachi',
            'buyerRegistrationType' => $sampleData['buyerRegistrationType'],
            'invoiceRefNo' => $sampleData['invoiceRefNo'] ?? '',
            'scenarioId' => $scenarioId,
            'items' => [
                array_merge([
                    'totalValues' => $item['totalValues'] ?? 0,
                    'fixedNotifiedValueOrRetailPrice' => $item['fixedNotifiedValueOrRetailPrice'] ?? 0.00,
                    'salesTaxWithheldAtSource' => $item['salesTaxWithheldAtSource'] ?? 0,
                    'extraTax' => $item['extraTax'] ?? 0,
                    'furtherTax' => $item['furtherTax'] ?? 0,
                    'sroScheduleNo' => $item['sroScheduleNo'] ?? '',
                    'fedPayable' => $item['fedPayable'] ?? 0,
                    'discount' => $item['discount'] ?? 0,
                    'sroItemSerialNo' => $item['sroItemSerialNo'] ?? ''
                ], $item)
            ]
        ];

        // Apply any overrides
        return array_merge($invoice, $overrides);
    }
}