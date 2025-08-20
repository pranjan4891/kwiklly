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
        Schema::create('vendor_admins', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->enum('user_type', ['admin', 'vendor']);
            
            // Common Fields
            $table->string('email')->unique();
            $table->string('password');
            $table->string('name');
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
            
            // Business Information
            $table->string('business_name')->nullable();
            $table->string('business_logo')->nullable();
            $table->string('business_category')->nullable();
            $table->text('business_description')->nullable();
            $table->string('business_contact_no')->nullable();
            $table->string('gstin')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('pan_image')->nullable();
            
            // Address Information
            $table->text('business_address')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('area')->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            
            // Banking Information
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_city')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('cancel_cheque_image')->nullable();
            
            // Vendor-Specific Fields
            $table->string('display_name')->nullable();
            $table->string('display_image')->nullable();
            $table->string('tan_image')->nullable();
            $table->string('tan_number')->nullable();
            $table->string('cin_image')->nullable();
            $table->string('cin_number')->nullable();
            $table->string('address_proof')->nullable();
            $table->string('address_proof_image')->nullable();
            
            // Personal Information (For Vendors)
            $table->string('personal_business_name')->nullable();
            $table->string('personal_pan')->nullable();
            $table->string('personal_pan_image')->nullable();
            $table->string('personal_address_proof')->nullable();
            $table->string('personal_address_proof_image')->nullable();
            $table->string('personal_cancel_cheque')->nullable();
            
            // Business Operations
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->decimal('minimum_order_value', 10, 2)->nullable();
            $table->integer('delivery_range')->nullable();
            $table->text('service_offered')->nullable();
            $table->decimal('delivery_charge', 10, 2)->nullable();
            $table->tinyInteger('delivery_charge_status')->nullable();
            $table->string('deliver_from')->nullable();
            
            // Security & Verification
            $table->string('remember_token')->nullable();
            $table->string('otp', 10)->nullable();
            $table->tinyInteger('otp_status')->nullable();
            
            // Miscellaneous
            $table->tinyInteger('is_delete')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_admins');
    }
};
