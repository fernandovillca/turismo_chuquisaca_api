<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_cultural_etiquettes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cultural_etiquette_id')
                ->constrained('cultural_etiquettes')
                ->onDelete('cascade');

            $table->string('name_detail', 150);
            $table->text('detail');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_cultural_etiquettes');
    }
};
