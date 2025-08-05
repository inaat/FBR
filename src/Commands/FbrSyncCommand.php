<?php

namespace Fbr\DigitalInvoicing\Commands;

use Illuminate\Console\Command;
use Fbr\DigitalInvoicing\Services\FbrReferenceService;
use Fbr\DigitalInvoicing\Models\Invoice;
use Fbr\DigitalInvoicing\Jobs\SubmitInvoiceJob;
use Illuminate\Support\Facades\Cache;

class FbrSyncCommand extends Command
{
    protected $signature = 'fbr:sync 
                            {--type=all : Type of data to sync (provinces, uom, doctypes, hscodes, sroitems, transtypes, all)}
                            {--cache-ttl=30 : Cache TTL in days}
                            {--submit-pending : Submit pending invoices}
                            {--force : Force sync even if cached}';
    
    protected $description = 'Sync reference data from FBR API and submit pending invoices';

    public function handle(FbrReferenceService $service): int
    {
        $type = $this->option('type');
        $cacheTtl = (int) $this->option('cache-ttl');
        $force = $this->option('force');
        $submitPending = $this->option('submit-pending');

        $this->info('🚀 Starting FBR operations...');

        try {
            if ($submitPending) {
                $this->submitPendingInvoices();
            }

            $this->syncReferenceData($service, $type, $cacheTtl, $force);

            $this->info('✅ FBR operations completed successfully!');
        } catch (\Exception $e) {
            $this->error('❌ Operation failed: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function syncReferenceData(FbrReferenceService $service, string $type, int $cacheTtl, bool $force): void
    {
        $this->info('📊 Syncing reference data...');

        $syncMethods = [
            'provinces' => 'syncProvinces',
            'uom' => 'syncUom',
            'doctypes' => 'syncDocTypes',
            'hscodes' => 'syncHsCodes',
            'sroitems' => 'syncSroItems',
            'transtypes' => 'syncTransactionTypes',
        ];

        if ($type === 'all') {
            foreach ($syncMethods as $method) {
                $this->{$method}($service, $cacheTtl, $force);
            }
        } elseif (isset($syncMethods[$type])) {
            $this->{$syncMethods[$type]}($service, $cacheTtl, $force);
        } else {
            $this->error("Invalid sync type: {$type}");
            $this->info('Available types: ' . implode(', ', array_keys($syncMethods)) . ', all');
        }
    }

    private function submitPendingInvoices(): void
    {
        $this->info('📤 Submitting pending invoices...');
        
        $pendingCount = Invoice::where('status', 'pending')->count();
        
        if ($pendingCount === 0) {
            $this->info('ℹ️  No pending invoices found.');
            return;
        }

        $this->info("📝 Found {$pendingCount} pending invoice(s).");

        if (!$this->confirm('Do you want to submit all pending invoices to FBR?')) {
            $this->info('⏭️  Skipping invoice submission.');
            return;
        }

        $bar = $this->output->createProgressBar($pendingCount);
        $bar->start();

        Invoice::where('status', 'pending')
            ->with('items')
            ->chunk(10, function ($invoices) use ($bar) {
                foreach ($invoices as $invoice) {
                    SubmitInvoiceJob::dispatch($invoice)->onQueue('fbr-invoices');
                    $bar->advance();
                }
            });

        $bar->finish();
        $this->newLine();
        $this->info("✅ Queued {$pendingCount} invoice(s) for submission.");
    }

    private function syncProvinces(FbrReferenceService $service, int $cacheTtl, bool $force): void
    {
        $cacheKey = 'fbr.provinces';
        
        if (!$force && Cache::has($cacheKey)) {
            $this->info('⏭️  Provinces already cached (use --force to refresh)');
            return;
        }

        $this->info('🗺️  Syncing provinces...');
        $provinces = $service->getProvinces();
        Cache::put($cacheKey, $provinces, now()->addDays($cacheTtl));
        $this->info('✅ Provinces synced: ' . count($provinces) . ' items');
    }

    private function syncUom(FbrReferenceService $service, int $cacheTtl, bool $force): void
    {
        $cacheKey = 'fbr.uom';
        
        if (!$force && Cache::has($cacheKey)) {
            $this->info('⏭️  UOM codes already cached (use --force to refresh)');
            return;
        }

        $this->info('📏 Syncing UOM codes...');
        $uom = $service->getUomCodes();
        Cache::put($cacheKey, $uom, now()->addDays($cacheTtl));
        $this->info('✅ UOM codes synced: ' . count($uom) . ' items');
    }

    private function syncDocTypes(FbrReferenceService $service, int $cacheTtl, bool $force): void
    {
        $cacheKey = 'fbr.doctypes';
        
        if (!$force && Cache::has($cacheKey)) {
            $this->info('⏭️  Document types already cached (use --force to refresh)');
            return;
        }

        $this->info('📄 Syncing document types...');
        $docTypes = $service->getDocumentTypeCodes();
        Cache::put($cacheKey, $docTypes, now()->addDays($cacheTtl));
        $this->info('✅ Document types synced: ' . count($docTypes) . ' items');
    }

    private function syncHsCodes(FbrReferenceService $service, int $cacheTtl, bool $force): void
    {
        $cacheKey = 'fbr.hscodes';
        
        if (!$force && Cache::has($cacheKey)) {
            $this->info('⏭️  HS codes already cached (use --force to refresh)');
            return;
        }

        $this->info('🏷️  Syncing HS codes...');
        $hsCodes = $service->getItemDescCodes();
        Cache::put($cacheKey, $hsCodes, now()->addDays($cacheTtl));
        $this->info('✅ HS codes synced: ' . count($hsCodes) . ' items');
    }

    private function syncSroItems(FbrReferenceService $service, int $cacheTtl, bool $force): void
    {
        $cacheKey = 'fbr.sroitems';
        
        if (!$force && Cache::has($cacheKey)) {
            $this->info('⏭️  SRO items already cached (use --force to refresh)');
            return;
        }

        $this->info('📋 Syncing SRO items...');
        $sroItems = $service->getSroItemCodes();
        Cache::put($cacheKey, $sroItems, now()->addDays($cacheTtl));
        $this->info('✅ SRO items synced: ' . count($sroItems) . ' items');
    }

    private function syncTransactionTypes(FbrReferenceService $service, int $cacheTtl, bool $force): void
    {
        $cacheKey = 'fbr.transtypes';
        
        if (!$force && Cache::has($cacheKey)) {
            $this->info('⏭️  Transaction types already cached (use --force to refresh)');
            return;
        }

        $this->info('💼 Syncing transaction types...');
        $transTypes = $service->getTransactionTypeCodes();
        Cache::put($cacheKey, $transTypes, now()->addDays($cacheTtl));
        $this->info('✅ Transaction types synced: ' . count($transTypes) . ' items');
    }
}
