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
        Schema::create('aen_evento_galeria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('aen_eventos')->onDelete('cascade');
            $table->string('link_materia')->nullable();
            $table->date('dia')->nullable();
            $table->text('descricao')->nullable();
            $table->string('pasta_aws')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aen_evento_galeria');
    }
};
