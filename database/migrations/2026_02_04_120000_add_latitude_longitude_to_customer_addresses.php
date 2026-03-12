<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('customer_addresses', 'latitude')) {
                $table->decimal('latitude', 10, 6)->nullable()->after('pincode');
            }
            if (!Schema::hasColumn('customer_addresses', 'longitude')) {
                $table->decimal('longitude', 10, 6)->nullable()->after('latitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            if (Schema::hasColumn('customer_addresses', 'latitude')) {
                $table->dropColumn('latitude');
            }
            if (Schema::hasColumn('customer_addresses', 'longitude')) {
                $table->dropColumn('longitude');
            }
        });
    }
};
