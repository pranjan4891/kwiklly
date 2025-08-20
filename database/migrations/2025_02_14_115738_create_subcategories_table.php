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
        Schema::create('subcategories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('va_id'); // Vendor ID (Foreign Key)
            $table->unsignedBigInteger('category_id'); // Vendor ID (Foreign Key)
            $table->string('name'); // Category name
            $table->string('image')->nullable(); // Category image (optional)
            $table->tinyInteger('is_active')->default(1); // 1 = Active, 0 = Inactive
            $table->tinyInteger('is_deleted')->default(0); 
            $table->timestamps();

            // Foreign key constraint (optional)
            // $table->foreign('v_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcategories');
    }
};
