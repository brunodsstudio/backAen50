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
        Schema::create('aen_concursos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('tipo_concurso_id')->constrained('aen_tipo_concurso')->onDelete('cascade');
            $table->string('link_foto')->nullable();
            $table->text('descricao')->nullable();
            $table->text('datas_horas')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aen_concursos');
    }
};
