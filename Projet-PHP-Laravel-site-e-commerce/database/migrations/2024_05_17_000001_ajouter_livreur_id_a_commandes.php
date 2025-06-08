<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->unsignedBigInteger('livreur_id')->nullable()->after('statut');
            $table->foreign('livreur_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropForeign(['livreur_id']);
            $table->dropColumn('livreur_id');
        });
    }
}; 