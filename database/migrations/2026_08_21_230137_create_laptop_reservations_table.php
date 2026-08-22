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
    Schema::create('laptop_reservations', function (Blueprint $table) {
        $table->id();
        $table->string('requester_name');
        $table->string('laptop_asset_number');
        $table->string('student_class');
        $table->string('teacher_name');
        $table->string('subject');
        $table->boolean('includes_charger')->default(false);
        $table->string('charger_code')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laptop_reservations');
    }
};
