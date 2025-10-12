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
        Schema::create('location_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->enum('contact_method', ['email', 'sms', 'whatsapp']);
            $table->string('contact_detail');
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendor_admins')->onDelete('cascade');
            $table->index(['vendor_id', 'contact_method']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_contacts');
    }
};
