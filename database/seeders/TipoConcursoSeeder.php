<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TipoConcursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            'Dança Kpop',
            'Arte/Ilustração',
            'Troféu',
            'Cosplay'
        ];

        foreach ($tipos as $tipo) {
            DB::table('aen_tipo_concurso')->insert([
                'nome' => $tipo,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
