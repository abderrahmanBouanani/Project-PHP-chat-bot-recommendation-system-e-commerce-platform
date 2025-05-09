<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facturation extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id', 'nom', 'prenom', 'ville', 'adresse',
        'region', 'code_postal', 'email', 'telephone', 'note_commande'
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
}
