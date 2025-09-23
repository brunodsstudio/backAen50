<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// $table->unsignedBigInteger('id_area')->nullable()->default(null);
    public function up(): void
    {
        Schema::create('tb_aen_images', function (Blueprint $table) {
            $table->id('int_Id');
            $table->string('vchr_ImgLink', 500)->nullable()->default(null);
            $table->string('vchr_ImgThumbLink', 500)->nullable()->default(null);
            $table->unsignedInteger('int_MateriaId')->nullable()->default(null);
            $table->string('vchr_Tipo', 50)->nullable()->default(null);
            $table->longText('vchr_Descricao')->nullable()->default(null);
            $table->dateTime('dt_Upload')->nullable()->default(null)->comment('data upload');
            $table->string('vchr_HRef', 500)->nullable()->default(null);
            $table->double('dl_SizeW')->nullable()->default(null);
            $table->double('dl_SizeH')->nullable()->default(null);
            $table->double('dl_Thumb_SizeW')->nullable()->default(null);
            $table->double('dl_Thumb_SizeH')->nullable()->default(null);
            $table->integer('int_Ordem')->nullable()->default(null); 
            $table->timestamps();
            $table->foreign('int_MateriaId')->references('id')->on('tb_aen_materias')->onDelete('cascade'); // Foreign key constraint    
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_aen_images');
    }
};
