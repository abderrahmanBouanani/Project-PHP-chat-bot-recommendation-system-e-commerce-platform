<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id', 'montant', 'type', 
        'numero_carte', 'date_expiration', 'email_paypal'
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
}
