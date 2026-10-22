<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->index();
            $table->string('unit_code', 9)->unique();
            $table->enum('type', ['board_game', 'console']);
            $table->string('condition', 100);
            $table->enum('status', ['available', 'borrowed'])->default('available');
            $table->text('description')->nullable();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
