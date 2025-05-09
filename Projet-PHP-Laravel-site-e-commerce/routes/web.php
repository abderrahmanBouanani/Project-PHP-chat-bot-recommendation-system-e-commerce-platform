<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminOrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProduitController; // Ensure this controller exists in the specified namespace
use App\Models\Cart;
use App\Models\Produit;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request as HttpRequest;
use App\Http\Controllers\LivreurController;

// ---connexion & signup--- 

// Afficher le formulaire de connexion
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

// Gérer l'envoi du formulaire de connexion
Route::post('/login', [LoginController::class, 'authenticate']);

// Redirections post-login
Route::get('/client_home', function () {
    return view('client-interface.index');
});

// Afficher le formulaire d'inscription
Route::get('/signup', [SignupController::class, 'showSignupForm'])->name('signup');

// Soumettre le formulaire d'inscription
Route::post('/signup', [SignupController::class, 'create']);




//---vendeur---
// Gérer l'envoi du formulaire d'ajout du produit
Route::post('/produit', [ProduitController::class, 'store'])->name('vendeur.addProduct');
Route::delete('/vendeur_shop/{id}', [ProduitController::class, 'destroy'])->name('produits.destroy');



Route::get('/vendeur_shop', [ProduitController::class, 'index'])->name('vendeur.shop');


//chargement des produits
Route::get('/api/produits', [ProduitController::class, 'getProduits']);



//Ajouter au panier
Route::post('/api/cart/add', [CartController::class,'addToCart']);

//Codes promo
Route::post('/coupon/apply', [CouponController::class, 'applyCoupon']);

// Pour calculer les totaux
Route::get('/cart/total', [CartController::class, 'getCartTotal']);
// Pour récupérer les produits du panier
Route::get('/cart', [CartController::class, 'getCart']);

// Pour supprimer un produit du panier
Route::delete('/client_cart/remove/{id}', [CartController::class, 'removeCartItem'])->name('remove_from_cart');

// Route pour mettre à jour la quantité d'un produit dans le panier
Route::post('/cart/update/{id}', [CartController::class, 'updateCartItem']);



// Routes pour gérer les totaux du panier
Route::post('/api/cart/update-totals', [App\Http\Controllers\CartController::class, 'updateTotals']);
Route::get('/api/cart/get-totals', [App\Http\Controllers\CartController::class, 'getTotals']);
// Route pour créer une commande
Route::post('/api/orders/create', [App\Http\Controllers\OrderController::class, 'createOrder']);


//Routes pour le client
Route::get('/client_home', function () {
    return view('client-interface.index', ['page' => 'ShopAll - Home']);
});

Route::get('/client_shop', function () {
    return view('client-interface.shop',['page' => 'ShopAll - Boutique']);
});

Route::get('/client_about', function () {
    return view('client-interface.about',['page' => 'ShopAll - A propos']);
});

Route::get('/client_service', function () {
    return view('client-interface.services',['page' => 'ShopAll - Services']);
});

Route::get('/client_contact', function () {
    return view('client-interface.contact',['page' => 'ShopAll - Contact']);
});

Route::get('/client_profile', function () {
    return view('client-interface.profilClient', ['user' => session('user')],['page' => 'ShopAll - Profile']);
});

Route::get('/client_cart', function () {
    return view('client-interface.cart',['page' => 'ShopAll - Panier']);
});

Route::get('/logout', function () {
    return view('welcome');
});

Route::get('/client/thankyou', function () {
    return view('client-interface.thankyou',['page' => 'ShopAll - Thank you']);
});

Route::get('/client/checkout', function () {
    return view('client-interface.checkout',['page' => 'ShopAll - Checkout']);
});


//Routes pour l'admin
Route::prefix('admin')->group(function () {
    Route::get('/home', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/dashboard/recent-orders', [AdminDashboardController::class, 'recentOrders'])->name('admin.dashboard.recent-orders');
    Route::get('/dashboard/chart-data', [AdminDashboardController::class, 'chartData'])->name('admin.dashboard.chart-data');
    Route::get('/commande', [AdminOrderController::class, 'index'])->name('admin.commandes');
    Route::get('/commande/{id}', [AdminOrderController::class, 'show'])->name('admin.commande.show');
    Route::post('/commande/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.commande.status');
    Route::get('/api/products/{commandeId}', [AdminOrderController::class, 'getProducts'])->name('admin.api.products');
    Route::get('/commande/search', [AdminOrderController::class, 'search'])->name('admin.commande.search');
    
    Route::get('/about', [AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/about', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    
    Route::get('/produit', [AdminProductController::class, 'index'])->name('admin.produits');
    Route::get('/produit/search', [AdminProductController::class, 'search'])->name('admin.produit.search');
    Route::get('/produit/categories', [AdminProductController::class, 'getCategories'])->name('admin.produit.categories');
    
    Route::get('/utilisateur', [AdminUserController::class, 'index'])->name('admin.utilisateurs');
    Route::get('/utilisateur/{id}', [AdminUserController::class, 'show'])->name('admin.utilisateur.show');
    Route::delete('/utilisateur/{id}', [AdminUserController::class, 'destroy'])->name('admin.utilisateur.destroy');
});

// Anciennes routes admin (à garder temporairement pour la compatibilité)
Route::get('/admin_home', [AdminController::class, 'dashboard']);
Route::get('/admin_commande', [AdminOrderController::class, 'index']);
Route::get('/admin_about', [AdminController::class, 'profile']);
Route::get('/admin_produit', [AdminProductController::class, 'index']);
Route::get('/admin_utilisateur', [AdminUserController::class, 'index']);

//Routes pour le vendeur

Route::get('/vendeur_home',function(){
    return view('vendeur-interface.vendeurHome',['page' => 'ShopAll - Home']);
});

Route::get('/vendeur_about',function(){
    return view('vendeur-interface.vendeurApropos',['page' => 'ShopAll - A propos']);
});


Route::get('/vendeur_contact',function(){
    return view('vendeur-interface.vendeurContact',['page' => 'ShopAll - Contact']);
});

Route::get('/vendeur_profile', function () {
    return view('vendeur-interface.vendeurProfile', ['user' => session('user')],['page' => 'ShopAll - Profile']);
});

Route::get('/vendeur_service',function(){
    return view('vendeur-interface.vendeurService',['page' => 'ShopAll - Services']);
});


//Routes pour le livreur

Route::get('/livreur_livraison', [LivreurController::class, 'index']);
Route::post('/livreur/commande/{id}/accepter', [LivreurController::class, 'accepter']);
Route::post('/livreur/commande/{id}/livree', [LivreurController::class, 'livree']);
Route::post('/livreur/commande/{id}/update-status', [LivreurController::class, 'updateStatus']);
Route::get('/livreur/commande/{id}/produits', [LivreurController::class, 'getProducts']);

Route::get('/livreur_profile',function(){
    return view('livreur-interface.profil-livreur',['user' => session('user')]);
});

Route::post('/livreur/profile/update', [LivreurController::class, 'updateProfile']);

// Routes pour le livreur
Route::post('/livreur/update-profile', [LivreurController::class, 'updateProfile'])->name('livreur.updateProfile');

// Routes API pour la recherche
Route::get('/api/admin/commande/search', [AdminOrderController::class, 'search']);
Route::get('/api/admin/produit/search', [AdminProductController::class, 'search']);
Route::get('/api/livreur/livraison/search', [LivreurController::class, 'search']);
Route::get('/api/admin/users/search', [AdminUserController::class, 'search']);
