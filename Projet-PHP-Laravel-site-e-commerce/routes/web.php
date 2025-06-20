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
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProduitController; // Ensure this controller exists in the specified namespace
use App\Models\Cart;
use App\Models\Produit;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request as HttpRequest;
use App\Http\Controllers\LivreurController;
use App\Http\Controllers\LivreurDashboardController;

// ---connexion & signup---

// Afficher le formulaire de connexion
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

// Gérer l'envoi du formulaire de connexion
Route::post('/login', [LoginController::class, 'authenticate']);

// Gérer la déconnexion
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirections post-login
Route::get('/client_home', function () {
    return view('client-interface.index');
});

// Afficher le formulaire d'inscription
Route::get('/signup', [SignupController::class, 'showSignupForm'])->name('signup');

// Soumettre le formulaire d'inscription
Route::post('/signup', [SignupController::class, 'create']);

// Routes pour les pages publiques
Route::get('/public/about', function () {
    return view('public.about', ['page' => 'ShopAll - À propos']);
});

Route::get('/public/services', function () {
    return view('public.services', ['page' => 'ShopAll - Services']);
});

Route::get('/public/contact', function () {
    return view('public.contact', ['page' => 'ShopAll - Contact']);
});

// Routes pour la réinitialisation du mot de passe
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');





//---vendeur---
// Gérer l'envoi du formulaire d'ajout du produit
Route::post('/produit', [ProduitController::class, 'store'])->name('vendeur.addProduct');
// Route pour mettre à jour la quantité d'un produit
Route::post('/vendeur_shop/{id}/quantity', [ProduitController::class, 'updateQuantity'])->name('produits.updateQuantity');
// Route pour mettre un produit hors stock
Route::post('/vendeur_shop/{id}/out-of-stock', [ProduitController::class, 'destroy'])->name('produits.destroy');

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
// Route pour télécharger la facture PDF d'une commande
Route::get('/commande/{id}/facture', [App\Http\Controllers\OrderController::class, 'downloadInvoice'])->name('commande.facture');


// Routes pour l'administration des produits
Route::prefix('admin')->middleware(['web'])->group(function () {
    // Route principale pour afficher la liste des produits
    Route::get('/produits', [AdminProductController::class, 'index'])->name('admin.produits');
    
    // Route pour la recherche AJAX (doit être avant les routes avec paramètres)
    Route::get('/produits/search', [AdminProductController::class, 'search'])->name('admin.produits.search');
    
    // Routes pour la gestion des produits (CRUD)
    Route::get('/produits/create', [AdminProductController::class, 'create'])->name('admin.produits.create');
    Route::post('/produits', [AdminProductController::class, 'store'])->name('admin.produits.store');
    Route::get('/produits/{id}', [AdminProductController::class, 'show'])->name('admin.produits.show');
    Route::get('/produits/{id}/edit', [AdminProductController::class, 'edit'])->name('admin.produits.edit');
    Route::put('/produits/{id}', [AdminProductController::class, 'update'])->name('admin.produits.update');
    Route::delete('/produits/{id}', [AdminProductController::class, 'destroy'])->name('admin.produits.delete');
});

// Routes pour le client
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
})->name('client.profile');

// Afficher le formulaire d'édition du profil
Route::get('/client/profile/edit', function () {
    $user = session('user');
    if (!$user) {
        return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
    }
    return view('client-interface.editProfilClient', ['user' => $user, 'page' => 'ShopAll - Modifier mon profil']);
})->name('client.profile.edit');

// Mettre à jour le profil
Route::put('/client/profile/update', [App\Http\Controllers\UserController::class, 'update'])->name('client.profile.update');

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

// Routes pour les commandes client
Route::get('/client/commandes', [App\Http\Controllers\ClientOrderController::class, 'index'])->name('client.commandes');
Route::get('/client/commandes/{id}', [App\Http\Controllers\ClientOrderController::class, 'show'])->name('client.commande.show');


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



Route::get('/vendeur_profile', function () {
    return view('vendeur-interface.vendeurProfile', ['user' => session('user')],['page' => 'ShopAll - Profile']);
})->name('vendeur.profile');

// Afficher le formulaire d'édition du profil
Route::get('/vendeur/profile/edit', function () {
    $user = session('user');
    if (!$user) {
        return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
    }
    return view('vendeur-interface.editProfilVendeur', ['user' => $user, 'page' => 'ShopAll - Modifier mon profil']);
})->name('vendeur.profile.edit');

// Mettre à jour le profil
Route::put('/vendeur/profile/update', [App\Http\Controllers\UserController::class, 'update'])->name('vendeur.profile.update');

//Routes pour le livreur

// Dashboard du livreur
Route::get('/livreur/dashboard', [LivreurDashboardController::class, 'dashboard'])->name('livreur.dashboard');

// Routes pour les commandes du livreur
Route::prefix('livreur')->group(function () {
    Route::get('/commandes', [LivreurController::class, 'index'])->name('livreur.commandes');
    Route::get('/commandes/disponibles', [LivreurController::class, 'livraisonsDisponibles'])->name('livreur.livraisons.disponibles');
    Route::get('/commandes/mes-livraisons', [LivreurController::class, 'mesLivraisons'])->name('livreur.mes.livraisons');
    Route::get('/commande/actuelle', [LivreurController::class, 'commandeActuelle'])->name('livreur.commande.actuelle');
    Route::get('/commande/{id}/details', [LivreurController::class, 'getCommandeDetails'])->name('livreur.commande.details');
    Route::get('/commande/{id}/produits', [LivreurController::class, 'getProducts'])->name('livreur.commande.produits');
    Route::post('/commande/{id}/accepter', [LivreurController::class, 'accepter'])->name('livreur.accepter');
    Route::post('/commande/{id}/livree', [LivreurController::class, 'livree'])->name('livreur.livree');
    Route::post('/commande/{id}/status', [LivreurController::class, 'updateStatus'])->name('livreur.commande.status');
    Route::get('/commandes/search', [LivreurController::class, 'search'])->name('livreur.commandes.search');
    Route::get('/profile', [LivreurController::class, 'profile'])->name('livreur.profile');
    Route::post('/update-profile', [LivreurController::class, 'updateProfile'])->name('livreur.update-profile');
});

// Routes API pour la recherche
Route::get('/api/admin/commande/search', [AdminOrderController::class, 'search']);
Route::get('/api/admin/produit/search', [AdminProductController::class, 'search']);
Route::get('/api/livreur/livraison/search', [LivreurController::class, 'search']);
Route::get('/api/admin/users/search', [AdminUserController::class, 'search']);

// Chatbot Route
Route::post('/chatbot/ask', [\App\Http\Controllers\ChatController::class, 'ask']);


// Route pour obtenir le nombre d'articles dans le panier
Route::get('/api/cart/count', [CartController::class, 'getCartCount']);

// --- Livreur ---
Route::get('/livreur/livraisons-disponibles', [LivreurController::class, 'livraisonsDisponibles'])->name('livreur.livraisons.disponibles');
Route::get('/livreur/mes-livraisons', [LivreurController::class, 'mesLivraisons'])->name('livreur.mes.livraisons');
Route::get('/livreur/commande-actuelle', [LivreurController::class, 'commandeActuelle'])->name('livreur.commande.actuelle');
Route::post('/livreur/accepter/{id}', [LivreurController::class, 'accepter'])->name('livreur.accepter');
Route::post('/livreur/livree/{id}', [LivreurController::class, 'livree'])->name('livreur.livree');







