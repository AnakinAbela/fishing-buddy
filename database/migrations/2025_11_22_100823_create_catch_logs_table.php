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
        Schema::create('catch_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('fishing_spot_id')->nullable()->constrained()->nullOnDelete();
            $table->string('species');
            $table->decimal('weight_kg', 8, 2)->nullable();
            $table->decimal('length_cm', 6, 2)->nullable();
            $table->decimal('depth_m', 6, 2)->nullable();
            $table->string('photo_path')->nullable();
            $table->enum('visibility', ['public','friends','private'])->default('public');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catch_logs');
    }
};
