<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipality_translations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('municipality_id')
                ->constrained('municipalities')
                ->onDelete('cascade');

            $table->foreignId('language_id')
                ->constrained('languages')
                ->onDelete('cascade');

            $table->string('name', 100)
                ->unique();

            $table->string('short_description', 200);

            $table->text('long_description')
                ->nullable();

            $table->string('address', 150);

            $table->timestamps();

            $table->unique(['municipality_id', 'language_id']);

            $table->index('language_id');

            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipality_translations');
    }
};
