<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prix_unitaire',
        'categorie',
        'quantite',
        'image',// image binaire
        'description',
        'vendeur_id'
    ];
    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
    }

    public function commandes()
    {
        return $this->belongsToMany(Commande::class, 'commande_produit', 'produit_id', 'commande_id')
                    ->withPivot('quantite')
                    ->withTimestamps();
    }
}
