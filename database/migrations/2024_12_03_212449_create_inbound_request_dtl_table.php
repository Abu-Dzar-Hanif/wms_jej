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
        Schema::create('inbound_request_dtl', function (Blueprint $table) {
            $table->id();
            $table->integer('inbound_request_id')->default(0);
            $table->integer('sku_id')->default(0);
            $table->integer('qty')->default(0);
            $table->integer('qty_awal')->default(0);
            $table->integer('qty_sisa')->default(0);
            $table->text('remarks')->nullable()->default(null);
            $table->integer('replenish_id')->default(0);
            $table->integer('inbound_request_dtl_status')->default(0)->comment('0 = alocated, 1 = partial recieved, 2 = full recieved');
            $table->datetime('status_partial_recieved')->nullable()->default(null);
            $table->datetime('status_full_recieved')->nullable()->default(null);
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
        Schema::dropIfExists('inbound_request_dtl');
    }
};
