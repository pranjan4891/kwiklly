<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->onDelete('set null');
            $table->decimal('coupon_discount', 10, 2)->default(0);
            $table->dateTime('delivery_time')->nullable();
            $table->decimal('sub_total', 12, 2);
            $table->decimal('delivery_fee', 12, 2)->default(0);
            $table->decimal('final_amount', 12, 2);
            $table->enum('delivery_status', ['pending', 'packed', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->foreignId('delivery_partner_id')->nullable()->constrained('delivery_partners')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_orders');
    }
};
