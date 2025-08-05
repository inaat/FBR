<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fbr_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fbr_invoice_id')->constrained()->onDelete('cascade');
            $table->string('fbr_item_invoice_number')->nullable()->index();
            $table->string('hs_code');
            $table->text('product_description');
            $table->string('rate');
            $table->string('uom');
            $table->decimal('quantity', 15, 4);
            $table->decimal('total_values', 15, 2)->default(0);
            $table->decimal('value_sales_excluding_st', 15, 2);
            $table->decimal('fixed_notified_value_or_retail_price', 15, 2)->default(0);
            $table->decimal('sales_tax_applicable', 15, 2);
            $table->decimal('sales_tax_withheld_at_source', 15, 2)->default(0);
            $table->decimal('extra_tax', 15, 2)->default(0);
            $table->decimal('further_tax', 15, 2)->default(0);
            $table->string('sro_schedule_no')->nullable();
            $table->decimal('fed_payable', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->string('sale_type');
            $table->string('sro_item_serial_no')->nullable();
            $table->string('status')->default('pending'); // pending, valid, invalid
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index('hs_code');
            $table->index('sale_type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('fbr_invoice_items');
    }
};