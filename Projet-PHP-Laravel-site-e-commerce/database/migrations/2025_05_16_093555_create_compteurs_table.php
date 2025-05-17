<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compteurs', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->string('categorie');
            $table->integer('nombre_clique')->default(1);
            $table->timestamps();
            
            // Ajouter un index pour améliorer les performances des requêtes
            $table->index(['client_id', 'categorie']);
            
            // Ajouter une contrainte unique pour éviter les doublons
            $table->unique(['client_id', 'categorie']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compteurs');
    }
};
