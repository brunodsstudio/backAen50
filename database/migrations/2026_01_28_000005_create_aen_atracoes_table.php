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
        Schema::create('aen_atracoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('tipo_atracao_id')->constrained('aen_tipo_atracao')->onDelete('cascade');
            $table->string('link_foto')->nullable();
            $table->string('link_instagram')->nullable();
            $table->string('link_perfil')->nullable();
            $table->text('descricao')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aen_atracoes');
    }
};
