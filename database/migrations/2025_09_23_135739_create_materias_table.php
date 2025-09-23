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
        Schema::create('tb_aen_materias', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('dt_post')->nullable()->default(null);
            $table->string('vchr_autor', 200)->nullable()->default(null);
            $table->unsignedBigInteger('int_autor')->nullable()->default(null);
            $table->string('vchr_lide', 500)->nullable()->default(null);
            $table->string('vchr_titulo', 300)->nullable()->default(null);
            $table->longText('vchr_conteudo')->nullable();
            $table->string('vchr_area', 100)->nullable()->default(null);
            $table->unsignedBigInteger('id_area')->nullable()->default(null);
            $table->string('vchr_tags', 500)->nullable()->default(null);
            $table->string('vchr_FontLink', 200)->nullable()->default(null);
            $table->string('vchr_LinkTitulo', 300)->nullable()->default(null);
            $table->string('vchr_MainSubject', 300)->nullable()->default(null);
            $table->dateTime('dt_alterado')->nullable()->default(null);
            $table->string('vchr_GalDir', 300)->nullable()->default(null);
            $table->string('og_url', 300)->nullable()->default(null);
            $table->string('og_type', 100)->nullable()->default(null);
            $table->string('og_title', 300)->nullable()->default(null);
            $table->string('og_description', 500)->nullable()->default(null);
            $table->string('og_image', 500)->nullable()->default(null);
            $table->string('vchr_s3_storage', 500)->nullable()->default(null);
            $table->boolean('bool_onLine')->nullable()->default(0)->comment('se online');
            $table->boolean('bool_home')->nullable()->default(0)->comment('se apa. na home');
            $table->boolean('base64Format')->nullable()->default(null);
            $table->char('materiaUUID', 36)->nullable()->default(null);
            $table->integer('IdSocialIconTemplate')->nullable()->default(null);   
            $table->foreign('int_autor')->references('int_Id')->on('tb_aen_writers'); // Foreign key constraint 
            $table->foreign('id_area')->references('int_Id')->on('tb_aen_area'); // Foreign key constraint 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_aen_materias');
    }
};
