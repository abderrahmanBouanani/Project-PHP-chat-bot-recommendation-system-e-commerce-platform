<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LivreurDashboardController extends Controller
{
    /**
     * Affiche le tableau de bord du livreur avec les statistiques et le graphique
     */
    public function dashboard()
    {
        if (!session('user') || session('user')['type'] !== 'livreur') {
            return redirect('/')->with('error', 'Accès réservé aux livreurs.');
        }

        $livreurId = session('user')['id'];
        $dates = [];
        $totals = [];
        
        $livraisons_par_jour = Commande::where('livreur_id', $livreurId)
            ->where('statut', 'Livrée')
            ->where('updated_at', '>=', Carbon::now()->subDays(30))
            ->select(DB::raw('DATE(updated_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Ajout du débogage avant le return
        \Log::info('Données du graphique', [
            'livreur_id' => $livreurId,
            'livraisons_par_jour' => $livraisons_par_jour->toArray(),
            'dates' => $dates,
            'totals' => $totals,
            'requete_sql' => Commande::where('livreur_id', $livreurId)
                ->where('statut', 'Livrée')
                ->where('updated_at', '>=', Carbon::now()->subDays(30))
                ->toSql()
        ]);

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $formattedDate = Carbon::now()->subDays($i)->format('d/m');
            
            $dates[] = $formattedDate;
            $totals[] = $livraisons_par_jour->get($date, (object)['total' => 0])->total;
        }

        // Statistiques générales
        $stats = [
            'total_livraisons' => Commande::where('livreur_id', $livreurId)->count(),
            'livraisons_en_cours' => Commande::where('livreur_id', $livreurId)
                ->where('statut', 'En cours de livraison')
                ->count(),
            'livraisons_terminees' => Commande::where('livreur_id', $livreurId)
                ->where('statut', 'Livrée')
                ->count(),
            'livraisons_disponibles' => Commande::where('statut', 'Confirmée')
                ->whereNull('livreur_id')
                ->count()
        ];

        // Commandes récentes triées par date de mise à jour
        $commandes_recentes = Commande::where('livreur_id', $livreurId)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('livreur-interface.dashboard', [
            'stats' => $stats,
            'dates' => $dates,
            'totals' => $totals,
            'commandes_recentes' => $commandes_recentes
        ]);
    }

    /**
     * Fournit les données pour le graphique des livraisons des 7 derniers jours
     */
    public function chartData()
    {
        $livreurId = session('user')['id'];
        $dates = [];
        $totals = [];
        
        $livraisons_par_jour = Commande::where('livreur_id', $livreurId)
            ->select(DB::raw('DATE(updated_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get()
            ->keyBy('date');

        // Récupérer les 7 derniers jours
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $formattedDate = Carbon::now()->subDays($i)->format('d/m');
            
            $dates[] = $formattedDate;
            $totals[] = $livraisons_par_jour->get($date, (object)['total' => 0])->total;
        }

        return response()->json([
            'dates' => $dates,
            'totals' => $totals
        ]);
    }
} 