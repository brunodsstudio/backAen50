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

        Schema::create('tb_aen_area', function (Blueprint $table) {
                $table->id('int_Id');
                $table->string('vchr_AreaNome');
                $table->string('vchr_Tag')->nullable();
                $table->enum('type', ['bd', 'pasta'])->nullable();
                $table->boolean('b_menu')->default(false);
                $table->integer('int_rolePermission')->default(0);
                $table->timestamps();
            });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_aen_area');
    }
};
