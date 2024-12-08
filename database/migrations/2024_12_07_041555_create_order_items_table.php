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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned();  // Corrected the column definition
            $table->bigInteger('order_id')->unsigned();  // Corrected the column definition
            $table->decimal('price');
            $table->integer('quantity');
            $table->longText('options')->nullable();
            $table->boolean('rstatus')->default(false);
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');  // Fixed the foreign key syntax
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');  // Fixed the column name and foreign key syntax
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
