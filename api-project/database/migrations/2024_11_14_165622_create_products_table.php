<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('product_name')->nullable();
        $table->string('brands')->nullable();
        $table->string('categories')->nullable();
        $table->string('labels')->nullable();
        $table->text('ingredients')->nullable();
        $table->string('countries')->nullable();
        $table->timestamp('imported_t')->nullable();
        $table->string('status')->default('imported');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
