<?php
namespace Fbr\DigitalInvoicing\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Fbr\DigitalInvoicing\FbrDigitalInvoicingServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            FbrDigitalInvoicingServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('fbr-digital-invoicing.bearer_token', 'test-token');
        $app['config']->set('fbr-digital-invoicing.sandbox', true);
    }
}