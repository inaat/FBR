<?php

namespace Fbr\DigitalInvoicing\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Fbr\DigitalInvoicing\Services\FbrDigitalInvoicingService;
use Fbr\DigitalInvoicing\Models\Invoice;
use Fbr\DigitalInvoicing\DTOs\InvoiceResponse;
use Fbr\DigitalInvoicing\Exceptions\FbrDigitalInvoicingException;
use Illuminate\Support\Facades\Log;

class SubmitInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $maxExceptions = 2;
    public int $timeout = 120;

    public function __construct(
        private Invoice $invoice,
        private bool $validateFirst = true
    ) {}

    public function middleware(): array
    {
        return [
            new WithoutOverlapping("submit-invoice-{$this->invoice->id}")
        ];
    }

    public function handle(FbrDigitalInvoicingService $service): void
    {
        try {
            Log::info('Starting invoice submission job', [
                'invoice_id' => $this->invoice->id,
                'attempt' => $this->attempts()
            ]);

            $invoiceData = $this->invoice->toInvoiceData();

            if ($this->validateFirst) {
                // First validate the invoice
                $validationResponse = $service->validateInvoiceData($invoiceData);
                
                if (!$validationResponse->isValid()) {
                    $this->handleValidationFailure($validationResponse);
                    return;
                }

                Log::info('Invoice validation successful', [
                    'invoice_id' => $this->invoice->id
                ]);
            }

            // Submit the invoice
            $response = $service->postInvoiceData($invoiceData);
            
            if ($response->isValid()) {
                $this->handleSubmissionSuccess($response);
            } else {
                $this->handleSubmissionFailure($response);
            }

        } catch (FbrDigitalInvoicingException $e) {
            $this->handleException($e);
            
            // If it's a client error (400-499), don't retry
            if ($e->getCode() >= 400 && $e->getCode() < 500) {
                $this->fail($e);
            } else {
                throw $e; // Will be retried
            }
        } catch (\Exception $e) {
            $this->handleException($e);
            throw $e; // Will be retried
        }
    }

    private function handleValidationFailure(InvoiceResponse $response): void
    {
        $errors = $response->getErrors();
        
        $this->invoice->update([
            'status' => 'failed',
            'validation_response' => [
                'dated' => $response->dated,
                'validationResponse' => $response->validationResponse
            ],
            'error_message' => implode('; ', $errors)
        ]);

        // Update item statuses if available
        if ($response->validationResponse?->invoiceStatuses) {
            foreach ($response->validationResponse->invoiceStatuses as $index => $status) {
                $item = $this->invoice->items->get($index);
                if ($item) {
                    $item->update([
                        'status' => $status->statusCode === '00' ? 'valid' : 'invalid',
                        'error_message' => $status->error
                    ]);
                }
            }
        }

        Log::warning('Invoice validation failed', [
            'invoice_id' => $this->invoice->id,
            'errors' => $errors
        ]);
    }

    private function handleSubmissionSuccess(InvoiceResponse $response): void
    {
        $this->invoice->update([
            'fbr_invoice_number' => $response->invoiceNumber,
            'status' => 'submitted',
            'validation_response' => [
                'invoiceNumber' => $response->invoiceNumber,
                'dated' => $response->dated,
                'validationResponse' => $response->validationResponse
            ],
            'error_message' => null,
            'submitted_at' => now()
        ]);

        // Update item statuses
        if ($response->validationResponse?->invoiceStatuses) {
            foreach ($response->validationResponse->invoiceStatuses as $index => $status) {
                $item = $this->invoice->items->get($index);
                if ($item) {
                    $item->update([
                        'fbr_item_invoice_number' => $status->invoiceNo,
                        'status' => $status->statusCode === '00' ? 'valid' : 'invalid',
                        'error_message' => $status->error
                    ]);
                }
            }
        }

        Log::info('Invoice submitted successfully', [
            'invoice_id' => $this->invoice->id,
            'fbr_invoice_number' => $response->invoiceNumber
        ]);
    }

    private function handleSubmissionFailure(InvoiceResponse $response): void
    {
        $errors = $response->getErrors();
        
        $this->invoice->update([
            'status' => 'failed',
            'validation_response' => [
                'dated' => $response->dated,
                'validationResponse' => $response->validationResponse
            ],
            'error_message' => implode('; ', $errors)
        ]);

        Log::error('Invoice submission failed with validation errors', [
            'invoice_id' => $this->invoice->id,
            'errors' => $errors
        ]);
    }

    private function handleException(\Exception $e): void
    {
        $this->invoice->update([
            'status' => 'failed',
            'error_message' => $e->getMessage()
        ]);

        Log::error('Invoice submission job failed with exception', [
            'invoice_id' => $this->invoice->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        $this->invoice->update([
            'status' => 'failed',
            'error_message' => $exception->getMessage()
        ]);

        Log::error('Invoice submission job permanently failed', [
            'invoice_id' => $this->invoice->id,
            'error' => $exception->getMessage()
        ]);
    }
}
