<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('commande_produit', function (Blueprint $table) {
            $table->foreignId('commande_id')->constrained();
            $table->foreignId('produit_id')->constrained();
            $table->integer('quantite');
            $table->primary(['commande_id', 'produit_id']);
            $table->timestamps(); // Ajout des timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commande_produit');
    }
};
