<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->string('level', 3);
            $table->unsignedBigInteger('amount');
            $table->timestamps();

            $table->unique(['academic_year_id', 'level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};
