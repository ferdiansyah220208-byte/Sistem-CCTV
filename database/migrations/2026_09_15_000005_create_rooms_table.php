<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('floor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('layout_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 100);
            $table->string('code', 30)->nullable();
            $table->text('description')->nullable();
            $table->json('polygon_points')->nullable();
            $table->float('center_x')->nullable();
            $table->float('center_y')->nullable();
            $table->string('color', 20)->default('#3b82f6');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('name');
            $table->index('code');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};