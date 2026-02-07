<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipalities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('region_id')
                ->constrained('regions')
                ->onDelete('cascade');

            $table->string('name', 100)
                ->unique();

            $table->string('short_description', 200);

            $table->text('long_description')
                ->nullable();

            $table->string('address', 150);

            $table->decimal('latitud', 10, 8);
            $table->decimal('longitud', 10, 8);

            $table->string('image');

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipalities');
    }
};
