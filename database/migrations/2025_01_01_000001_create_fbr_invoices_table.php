<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fbr_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('fbr_invoice_number')->nullable()->index();
            $table->string('invoice_type');
            $table->date('invoice_date');
            $table->string('seller_ntn_cnic');
            $table->string('seller_business_name');
            $table->string('seller_province');
            $table->text('seller_address');
            $table->string('buyer_ntn_cnic');
            $table->string('buyer_business_name');
            $table->string('buyer_province');
            $table->text('buyer_address');
            $table->string('buyer_registration_type');
            $table->string('invoice_ref_no')->nullable();
            $table->string('scenario_id')->nullable();
            $table->json('validation_response')->nullable();
            $table->string('status')->default('pending'); // pending, submitted, validated, failed
            $table->text('error_message')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['seller_ntn_cnic', 'invoice_date']);
            $table->index(['buyer_ntn_cnic', 'invoice_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('fbr_invoices');
    }
};