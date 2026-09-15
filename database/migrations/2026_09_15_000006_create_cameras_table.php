<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cameras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('layout_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 100);
            $table->string('code', 50)->unique();
            $table->string('ip_address', 45)->nullable();
            $table->string('stream_url')->nullable();
            $table->string('photo')->nullable();
            $table->string('brand', 50)->nullable();
            $table->enum('type', ['dome', 'bullet', 'ptz', 'fisheye'])->default('dome');
            $table->string('resolution', 20)->nullable();
            $table->enum('status', ['online', 'offline', 'maintenance'])->default('offline');
            $table->float('pos_x')->nullable();
            $table->float('pos_y')->nullable();
            $table->integer('rotation')->default(0);
            $table->date('installed_at')->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('status');
            $table->index('type');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cameras');
    }
};