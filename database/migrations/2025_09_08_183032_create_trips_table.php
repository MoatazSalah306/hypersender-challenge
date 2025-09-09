<?php

use App\Enums\TripStatus;
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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('driver_id')->constrained('drivers')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');

            // Trip details
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable(); // end_time can be null until trip finishes

            $table->enum('status', TripStatus::values())->default(TripStatus::Scheduled->value);

            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['start_time', 'end_time']);
            $table->index(['driver_id', 'vehicle_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
