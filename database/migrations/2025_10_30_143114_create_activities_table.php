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
        Schema::create('activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->enum('type', ['SEKALI', 'TERJADWAL']);
            $table->date('tanggal')->nullable();
            $table->integer('hari')->nullable();
            $table->time('jam');
            $table->string('materi');
            $table->string('tempat');
            $table->foreignUuid('zone_id');
            $table->string('no_pj');
            $table->json('for_status_kawin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
