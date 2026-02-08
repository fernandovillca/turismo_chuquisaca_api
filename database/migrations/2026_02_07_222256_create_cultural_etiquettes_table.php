<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cultural_etiquettes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('municipality_id')
                ->constrained('municipalities')
                ->onDelete('cascade');

            $table->string('title', 100);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cultural_etiquettes');
    }
};
