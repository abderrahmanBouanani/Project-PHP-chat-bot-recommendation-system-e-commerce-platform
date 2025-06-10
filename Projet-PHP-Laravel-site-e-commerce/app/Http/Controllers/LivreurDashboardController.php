<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LivreurDashboardController extends Controller
{
    /**
     * Affiche le tableau de bord du livreur
     */
    public function dashboard()
    {
        // Vérifier si l'utilisateur est connecté et est un livreur
        if (!session('user') || session('user')['type'] !== 'livreur') {
            return redirect('/')->with('error', 'Accès réservé aux livreurs.');
        }

        $livreurId = session('user')['id'];

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

        // Créer un tableau pour les 7 derniers jours
        $dates = [];
        $totals = [];
        
        // Récupérer les livraisons des 7 derniers jours
        $livraisons_par_jour = Commande::where('livreur_id', $livreurId)
            ->where('statut', 'Livrée')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Remplir le tableau avec les 7 derniers jours
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $formattedDate = Carbon::now()->subDays($i)->format('d/m');
            
            $dates[] = $formattedDate;
            $totals[] = $livraisons_par_jour->get($date, (object)['total' => 0])->total;
        }

        // Commandes récentes
        $commandes_recentes = Commande::where('livreur_id', $livreurId)
            ->orderBy('created_at', 'desc')
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
     * Récupère les données pour le graphique des livraisons
     */
    public function chartData()
    {
        $livreurId = session('user')['id'];

        // Créer un tableau pour les 7 derniers jours
        $dates = [];
        $totals = [];
        
        // Récupérer les livraisons des 7 derniers jours
        $livraisons_par_jour = Commande::where('livreur_id', $livreurId)
            ->where('statut', 'Livrée')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Remplir le tableau avec les 7 derniers jours
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