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
        Schema::create('aen_eventos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao');
            $table->dateTime('realizacao')->nullable();
            $table->string('link_foto')->nullable();
            $table->string('link_logo')->nullable();
            $table->string('link_site')->nullable();
            $table->string('link_instagram')->nullable();
            $table->string('link_video')->nullable();
            $table->string('link_x')->nullable();
            $table->string('link_tiktok')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aen_eventos');
    }
};
