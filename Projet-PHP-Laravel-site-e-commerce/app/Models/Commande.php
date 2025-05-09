<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commande extends Model
{
   use HasFactory;

   protected $fillable = [
       'client_id', 'adresse', 'statut', 'total', 'methode_paiement', 'reduction'
   ];

   public function client()
   {
       return $this->belongsTo(User::class, 'client_id');
   }

   public function produits()
   {
       return $this->belongsToMany(Produit::class, 'commande_produit')
                   ->withPivot('quantite')
                   ->withTimestamps(false);
   }

   public function paiement()
   {
       return $this->hasOne(Paiement::class);
   }

   public function facturation()
   {
       return $this->hasOne(Facturation::class);
   }
}
