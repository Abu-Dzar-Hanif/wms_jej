<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('do_dtl', function (Blueprint $table) {
            $table->id();
            $table->integer('inbound_request_dtl_id')->default(0);
            $table->integer('sku_id')->default(0);
            $table->string('barcode')->nullable()->default(0);
            $table->integer('qty')->default(0);
            $table->integer('qtyAct')->default(0);
            $table->integer('status')->default(0);
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->integer('deleted_by')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('do_dtl');
    }
};
