<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TipoAtracaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            'Banda',
            'Dublador',
            'Cantor',
            'Ator',
            'Celebridade',
            'Food Truck',
            'Expositores',
            'Artist Alley',
            'Painel',
            'Estréia'
        ];

        foreach ($tipos as $tipo) {
            DB::table('aen_tipo_atracao')->insert([
                'nome' => $tipo,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
