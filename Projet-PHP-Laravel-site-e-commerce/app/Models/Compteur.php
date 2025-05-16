<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compteur extends Model
{
    use HasFactory;

   protected $fillable = [
       'client_id', 'produit_id', 'nombre_clique'
   ];
}
