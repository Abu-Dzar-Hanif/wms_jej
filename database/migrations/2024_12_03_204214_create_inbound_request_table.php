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
        Schema::create('inbound_request', function (Blueprint $table) {
            $table->id();
            $table->string('wms_number')->unique()->nullable()->default(null);
            $table->string('third_party_number')->nullable()->default(null);
            $table->string('inbound_request_type')->nullable()->default('normal');
            $table->integer('vendor_id')->default(0);
            $table->string('no_sj')->nullable()->default(null);
            $table->string('po_number')->nullable()->default(null);
            $table->text('remarks')->nullable()->default(null);
            $table->date('date')->nullable()->default(null);
            $table->string('reference')->nullable()->default(null)->comment('nomor sale order/project number kalo retur');
            $table->integer('status')->default(0)->comment('0=alocate,1=partial recived,2=full recieved');
            $table->integer('alocate')->default(0);
            $table->datetime('alocate_at')->nullable()->default(null);
            $table->integer('alocate_by')->default(0);
            $table->integer('warehouse_id')->default(0);
            $table->datetime('close_at')->nullable()->default(null);
            $table->integer('close_by')->default(0);
            $table->datetime('put_away_close_date')->nullable()->default(null);
            $table->integer('put_away_close_by')->default(0);
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
        Schema::dropIfExists('inbound_request');
    }
};
