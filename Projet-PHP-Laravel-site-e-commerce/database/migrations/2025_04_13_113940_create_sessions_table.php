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
    Schema::create('sessions', function (Blueprint $table) {
        $table->string('id')->primary(); // ID de la session (clé primaire)
        $table->foreignId('user_id')->nullable()->constrained('users')->index(); // Lien avec la table 'users'
        $table->string('ip_address', 45)->nullable(); // IP de l'utilisateur (longueur pour IPv6)
        $table->text('user_agent')->nullable(); // User Agent pour la session
        $table->longText('payload'); // Données de session
        $table->integer('last_activity')->index(); // Timestamp de la dernière activité
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
