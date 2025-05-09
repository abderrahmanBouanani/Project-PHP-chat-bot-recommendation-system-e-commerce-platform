<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Get recent orders for the dashboard
     */
    public function recentOrders()
    {
        try {
            $recentOrders = Commande::with(['client', 'produits'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'created_at' => $order->created_at,
                        'client' => $order->client ? [
                            'nom' => $order->client->nom,
                            'prenom' => $order->client->prenom
                        ] : null,
                        'produits' => $order->produits->map(function ($produit) {
                            return [
                                'nom' => $produit->nom,
                                'quantite' => $produit->pivot->quantite
                            ];
                        }),
                        'total' => $order->total
                    ];
                });

            return response()->json($recentOrders);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error fetching recent orders: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get chart data for the dashboard
     */
    public function chartData()
    {
        try {
            // Get monthly sales data for the last 6 months
            $monthlySales = DB::table('commandes')
                ->select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('COUNT(*) as order_count'),
                    DB::raw('COALESCE(SUM(total), 0) as total_sales')
                )
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Format data for charts
            $labels = [];
            $orderData = [];
            $revenueData = [];

            foreach ($monthlySales as $data) {
                $date = Carbon::createFromFormat('Y-m', $data->month);
                $labels[] = $date->format('M Y');
                $orderData[] = (int)$data->order_count;
                $revenueData[] = round((float)$data->total_sales, 2);
            }

            // Get top 5 products by sales
            $topProducts = DB::table('commande_produit')
                ->join('produits', 'commande_produit.produit_id', '=', 'produits.id')
                ->select(
                    'produits.nom',
                    DB::raw('SUM(commande_produit.quantite) as total_sold')
                )
                ->groupBy('produits.id', 'produits.nom')
                ->orderBy('total_sold', 'desc')
                ->take(5)
                ->get();

            // Get category distribution
            $categoryDistribution = DB::table('produits')
                ->select(
                    'categorie',
                    DB::raw('COUNT(*) as total_products')
                )
                ->groupBy('categorie')
                ->get();

            // Format category data for pie chart
            $categoryLabels = $categoryDistribution->pluck('categorie')->toArray();
            $categoryData = $categoryDistribution->pluck('total_products')->map(function ($value) {
                return (int)$value;
            })->toArray();

            // Format product data for pie chart
            $productLabels = $topProducts->pluck('nom')->toArray();
            $productData = $topProducts->pluck('total_sold')->map(function ($value) {
                return (int)$value;
            })->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'monthly_sales' => [
                        'labels' => $labels,
                        'data' => $revenueData
                    ],
                    'orders' => [
                        'labels' => $labels,
                        'data' => $orderData
                    ],
                    'top_products' => [
                        'labels' => $productLabels,
                        'data' => $productData
                    ],
                    'category_distribution' => [
                        'labels' => $categoryLabels,
                        'data' => $categoryData
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error fetching chart data: ' . $e->getMessage()
            ], 500);
        }
    }
}