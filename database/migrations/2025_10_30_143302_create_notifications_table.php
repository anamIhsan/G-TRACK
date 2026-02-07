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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('judul');
            $table->text('message');
            $table->enum('jenis_notification', ['TERJADWAL', 'SEKARANG']);
            $table->enum('jenis_pengiriman', ['SEKALI', 'TIAP_HARI'])->nullable();
            $table->date('tanggal')->nullable();
            $table->time('jam')->nullable();
            $table->integer('sent')->default(0);
            $table->foreignUuid('activity_id')->nullable();
            $table->foreignUuid('zone_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
