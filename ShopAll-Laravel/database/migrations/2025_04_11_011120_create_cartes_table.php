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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade'); // ID du client (associé à la table users)
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade'); // ID du produit (associé à la table products)
            $table->string('nom_produit'); // Nom du produit
            $table->string('image');
            $table->decimal('prix', 8, 2); // Prix du produit
            $table->integer('quantite')->default(1); // Quantité (initialisée à 1)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cartes');
    }
};
