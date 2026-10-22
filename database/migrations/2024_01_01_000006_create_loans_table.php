<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('unit_id')->constrained('units')->restrictOnDelete();
            $table->foreignId('processed_by')->constrained('users')->restrictOnDelete();
            $table->date('borrowed_at');
            $table->date('due_date');
            $table->date('returned_at')->nullable();
            $table->enum('status', ['borrowed', 'returned'])->default('borrowed');
            $table->unsignedInteger('late_days')->default(0);
            $table->decimal('fine_amount', 12, 2)->default(0.00);

            $table->index(['member_id', 'status']);
            $table->index(['unit_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
