<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('communities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('municipality_id')
                ->constrained('municipalities')
                ->onDelete('cascade');

            $table->string('name', 100)
                ->unique();

            $table->string('short_description', 200)
                ->nullable();

            $table->string('address', 150)
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('communities');
    }
};
