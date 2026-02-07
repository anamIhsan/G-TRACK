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
        Schema::create('base_metafield_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('value');
            $table->foreignUuid('user_id');
            $table->foreignUuid('metafield_user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('base_metafield_users');
    }
};
