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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('nama');
            $table->string('username');
            $table->integer('umur')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('gambar')->nullable();
            $table->string('no_tlp')->nullable();
            $table->enum('kelamin', ['PR', 'LK'])->nullable();
            $table->string('password');
            $table->string('hint_password');
            $table->enum('status_kawin', ['SUDAH', 'BELUM'])->default('BELUM');
            $table->boolean('status_request')->default(1);
            $table->enum('role', ['MASTER', 'ADMIN_DAERAH', 'USER'])->default('USER');

            $table->string('nfc_id')->nullable();
            $table->text('twibbon_user')->nullable();
            $table->text('reason_deleted')->nullable();
            $table->string('whatsapp_gateway_provider')->nullable();

            $table->foreignUuid('interest_id')->nullable();
            $table->foreignUuid('sub_interest_id')->nullable();
            $table->foreignUuid('age_category_id')->nullable();
            $table->foreignUuid('work_id')->nullable();
            $table->foreignUuid('zone_id')->nullable();
            $table->softDeletes();

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignUuid('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
