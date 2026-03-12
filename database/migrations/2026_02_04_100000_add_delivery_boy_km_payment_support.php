<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add distance (km) to vendor_orders for delivery boy km-wise payment
        Schema::table('vendor_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('vendor_orders', 'delivery_distance_km')) {
                $table->decimal('delivery_distance_km', 8, 2)->nullable()->after('delivery_partner_id');
            }
        });

        // Optional: add phone and is_active to delivery_partners for managing delivery boys
        Schema::table('delivery_partners', function (Blueprint $table) {
            if (!Schema::hasColumn('delivery_partners', 'phone')) {
                $table->string('phone', 20)->nullable()->after('charges_value');
            }
            if (!Schema::hasColumn('delivery_partners', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('phone');
            }
        });

        // Per-order payment record: delivery boy ko kitna pay karna hai (km * rate) and paid or not
        Schema::create('delivery_partner_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_partner_id')->constrained('delivery_partners')->cascadeOnDelete();
            $table->foreignId('vendor_order_id')->constrained('vendor_orders')->cascadeOnDelete();
            $table->decimal('distance_km', 8, 2);
            $table->decimal('rate_per_km', 10, 2);
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_reference')->nullable()->comment('UTR / transaction ref');
            $table->timestamps();

            $table->unique('vendor_order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_partner_payments');

        Schema::table('vendor_orders', function (Blueprint $table) {
            if (Schema::hasColumn('vendor_orders', 'delivery_distance_km')) {
                $table->dropColumn('delivery_distance_km');
            }
        });

        Schema::table('delivery_partners', function (Blueprint $table) {
            if (Schema::hasColumn('delivery_partners', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('delivery_partners', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
