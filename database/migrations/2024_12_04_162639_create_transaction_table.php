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
        Schema::create('transaction', function (Blueprint $table) {
            $table->id();
            $table->integer('do_dtl_id')->default(0);
            $table->integer('warehouse_id')->default(0);
            $table->integer('qty')->default(0);
            $table->integer('in_by')->default(0);
            $table->datetime('in_at')->nullable()->default(null);
            $table->integer('alocate')->default(0);
            $table->datetime('alocate_at')->nullable()->default(null);
            $table->integer('alocate_by')->default(0);
            $table->integer('qc_status')->default(0);
            $table->datetime('qc_at')->nullable()->default(null);
            $table->integer('qc_by')->default(0);
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
        Schema::dropIfExists('transaction');
    }
};
