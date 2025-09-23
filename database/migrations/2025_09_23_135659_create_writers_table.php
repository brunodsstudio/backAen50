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
        Schema::create('tb_aen_writers', function (Blueprint $table) {
            $table->id('int_Id');
            $table->string('vchr_Nome', 100);
            $table->string('vchr_Nick', 50);
            $table->longText('long_Card')->nullable()->default(null);
            $table->boolean('bool_Enable')->nullable()->default(null)->comment('habilitado s/n');
            $table->string('vchr_LinkFoto', 50)->nullable()->default(null);
            $table->string('vchr_LinkInta', 50)->nullable()->default(null);
            $table->string('vchr_Cargo', 50)->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('writers');
    }
};
