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
        Schema::create('sku_data', function (Blueprint $table) {
            $table->id();
            $table->string('sku_code')->unique()->default(null);
            $table->string('sku_name')->default(null);
            $table->integer('uom_id')->default(0);
            $table->integer('sku_type_id')->default(0);
            $table->integer('category_id')->default(0);
            $table->text('ket')->nullable()->default(null);
            $table->double('weight')->default(0);
            $table->double('height')->default(0);
            $table->double('length')->default(0);
            $table->double('width')->default(0);
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
        Schema::dropIfExists('sku_data');
    }
};
