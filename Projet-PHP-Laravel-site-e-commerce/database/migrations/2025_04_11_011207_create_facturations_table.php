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
        Schema::create('facturations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained();
            $table->string('nom');
            $table->string('prenom');
            $table->string('ville');
            $table->string('adresse');
            $table->string('region');
            $table->integer('code_postal');
            $table->string('email');
            $table->string('telephone');
            $table->text('note_commande');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturations');
    }
};
