<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tb_aen_area', function (Blueprint $table) {
            $table->boolean('bool_Enabled')->default(true)->after('int_rolePermission');
        });
        
        // Set all existing records to enabled (true)
        DB::table('tb_aen_area')->update(['bool_Enabled' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_aen_area', function (Blueprint $table) {
            $table->dropColumn('bool_Enabled');
        });
    }
};
