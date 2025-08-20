<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->onDelete('cascade');
            $table->string('variant_name')->nullable(); // e.g., "Red - XL"
            $table->decimal('variant_actual_price', 10, 2);
            $table->decimal('variant_selling_price', 10, 2);
            $table->decimal('variant_save_price_in_rs', 10, 2);
            $table->decimal('variant_save_price_in_percent', 5, 2);
            $table->integer('stock')->default(0);
            $table->string('sku')->nullable()->unique();
            $table->json('attributes')->nullable(); // e.g., {"size": "XL", "color": "Red"}
            $table->timestamps();
        });
    }



    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};

