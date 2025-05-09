<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaiementSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('paiements')
            ->join('commandes', 'paiements.commande_id', '=', 'commandes.id')
            ->update(['paiements.type' => DB::raw('commandes.methode_paiement')]);
    }
} 