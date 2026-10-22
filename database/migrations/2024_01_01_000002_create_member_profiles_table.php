<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('phone', 15);
            $table->string('rank_or_position', 100);
            $table->string('station_module', 100);
            $table->string('address', 255)->nullable();
            $table->string('photo', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_profiles');
    }
};
