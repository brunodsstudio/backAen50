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
        Schema::create('tb_aen_videos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('int_IdMateria')->nullable()->comment('ID da matéria associada');
            $table->string('vchr_VideoId', 50)->nullable()->comment('ID do vídeo no YouTube');
            $table->string('vchr_LinkVideo', 300)->nullable()->comment('Link do vídeo');
            $table->unsignedBigInteger('int_IdArea')->nullable()->comment('ID da área');
            $table->string('vchr_Titulo', 400)->nullable(false)->comment('Título do vídeo');
            $table->longText('vchr_Description')->nullable(false)->comment('Descrição do vídeo');
            $table->string('vchr_YTThumbDefault', 300)->nullable(false)->comment('Thumbnail padrão do YouTube');
            $table->string('vchr_YTThumbMedium', 300)->nullable(false)->comment('Thumbnail média do YouTube');
            $table->string('vchr_YTThumbHigh', 300)->nullable(false)->comment('Thumbnail alta do YouTube');
            $table->longText('vchr_Embed')->nullable(false)->comment('Código embed do vídeo');
            $table->string('vchr_ChannelId', 50)->nullable(false)->comment('ID do canal do YouTube');
            $table->string('vchr_tags', 400)->nullable(false)->comment('Tags do vídeo');
            $table->dateTime('dt_Publicado')->nullable()->comment('Data de publicação no YouTube');
            $table->timestamps();
            
            // Indexes para performance (sem foreign keys para permitir flexibilidade)
            $table->index('int_IdMateria');
            $table->index('int_IdArea');
            $table->index('vchr_VideoId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_aen_videos');
    }
};
