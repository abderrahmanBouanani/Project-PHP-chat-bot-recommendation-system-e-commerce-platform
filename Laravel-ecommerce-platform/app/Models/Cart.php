<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'produit_id', 'nom_produit', 'image', 'prix'];

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }

    public function paiement()
    {
        return $this->belongsTo(Paiement::class);
    }
}
