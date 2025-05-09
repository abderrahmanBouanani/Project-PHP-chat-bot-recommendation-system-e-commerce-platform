<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Client;
use App\Models\Vendeur;
use App\Models\Produit;
use App\Models\Commande;
use App\Models\Paiement;
use App\Models\Carte;
use App\Models\Paypal;
use App\Models\Facturation;
use App\Models\Livraison;
use App\Models\Livreur;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_relations_work_correctly()
    {
        // Création d'un utilisateur admin
        $userAdmin = User::factory()->create(['type' => 'admin']);
        $admin = Admin::factory()->create(['user_id' => $userAdmin->id]);

        // Création d'un utilisateur client
        $userClient = User::factory()->create(['type' => 'client']);
        $client = Client::factory()->create(['user_id' => $userClient->id]);

        // Création d'un utilisateur vendeur
        $userVendeur = User::factory()->create(['type' => 'vendeur']);
        $vendeur = Vendeur::factory()->create(['user_id' => $userVendeur->id]);

        // Tests des relations
        $this->assertInstanceOf(Admin::class, $userAdmin->admin);
        $this->assertEquals($userAdmin->id, $admin->user->id);
        
        $this->assertInstanceOf(Client::class, $userClient->client);
        $this->assertEquals($userClient->id, $client->user->id);
        
        $this->assertInstanceOf(Vendeur::class, $userVendeur->vendeurs->first());
        $this->assertEquals($userVendeur->id, $vendeur->user->id);
    }

    /** @test */
    public function vendeur_produit_relation_works()
    {
        $vendeur = Vendeur::factory()->create();
        $produit1 = Produit::factory()->create();
        $produit2 = Produit::factory()->create();

        $vendeur->produits()->attach([$produit1->id, $produit2->id]);

        $this->assertCount(2, $vendeur->produits);
        $this->assertInstanceOf(Produit::class, $vendeur->produits->first());
        $this->assertInstanceOf(Vendeur::class, $produit1->vendeurs->first());
        $this->assertEquals($produit1->id, $vendeur->produits->first()->id);
        $this->assertEquals($vendeur->id, $produit1->vendeurs->first()->id);
    }

    /** @test */
    public function client_commande_relation_works()
    {
        $client = Client::factory()->create();
        $commande1 = Commande::factory()->create(['client_id' => $client->id]);
        $commande2 = Commande::factory()->create(['client_id' => $client->id]);

        $this->assertCount(2, $client->commandes);
        $this->assertInstanceOf(Commande::class, $client->commandes->first());
        $this->assertInstanceOf(Client::class, $commande1->client);
        $this->assertEquals($client->id, $commande1->client->id);
    }

    /** @test */
    public function commande_produit_relation_works_with_pivot_data()
    {
        $commande = Commande::factory()->create();
        $produit1 = Produit::factory()->create();
        $produit2 = Produit::factory()->create();
        $quantite1 = 3;
        $quantite2 = 5;

        $commande->produits()->attach([
            $produit1->id => ['quantite' => $quantite1],
            $produit2->id => ['quantite' => $quantite2]
        ]);

        $this->assertCount(2, $commande->produits);
        $this->assertInstanceOf(Produit::class, $commande->produits->first());
        $this->assertEquals($quantite1, $commande->produits->find($produit1->id)->pivot->quantite);
        $this->assertEquals($quantite2, $commande->produits->find($produit2->id)->pivot->quantite);
        $this->assertInstanceOf(Commande::class, $produit1->commandes->first());
    }

    /** @test */
    public function commande_paiement_relation_works()
    {
        $commande = Commande::factory()->create();
        $paiement1 = Paiement::factory()->create(['commande_id' => $commande->id]);
        $paiement2 = Paiement::factory()->create(['commande_id' => $commande->id]);

        $this->assertCount(2, $commande->paiements);
        $this->assertInstanceOf(Paiement::class, $commande->paiements->first());
        $this->assertInstanceOf(Commande::class, $paiement1->commande);
        $this->assertEquals($commande->id, $paiement1->commande->id);
    }

    /** @test */
    public function paiement_methods_relations_work()
    {
        $paiementCarte = Paiement::factory()->create(['type' => 'carte']);
        $carte = Carte::factory()->create(['paiement_id' => $paiementCarte->id]);

        $paiementPaypal = Paiement::factory()->create(['type' => 'paypal']);
        $paypal = Paypal::factory()->create(['paiement_id' => $paiementPaypal->id]);

        $paiementFacture = Paiement::factory()->create(['type' => 'facturation']);
        $facture = Facturation::factory()->create(['paiement_id' => $paiementFacture->id]);

        $this->assertInstanceOf(Carte::class, $paiementCarte->carte);
        $this->assertEquals($paiementCarte->id, $carte->paiement->id);
        
        $this->assertInstanceOf(Paypal::class, $paiementPaypal->paypal);
        $this->assertEquals($paiementPaypal->id, $paypal->paiement->id);
        
        $this->assertInstanceOf(Facturation::class, $paiementFacture->facturation);
        $this->assertEquals($paiementFacture->id, $facture->paiement->id);
    }

    /** @test */
    public function livraison_relations_work()
    {
        $livreur = Livreur::factory()->create();
        $commande = Commande::factory()->create();
        $livraison1 = Livraison::factory()->create([
            'commande_id' => $commande->id,
            'livreur_id' => $livreur->id
        ]);
        $livraison2 = Livraison::factory()->create([
            'commande_id' => $commande->id,
            'livreur_id' => $livreur->id
        ]);

        $this->assertCount(2, $commande->livraisons);
        $this->assertCount(2, $livreur->livraisons);
        $this->assertInstanceOf(Livraison::class, $commande->livraisons->first());
        $this->assertInstanceOf(Livraison::class, $livreur->livraisons->first());
        $this->assertInstanceOf(Commande::class, $livraison1->commande);
        $this->assertInstanceOf(Livreur::class, $livraison1->livreur);
        $this->assertEquals($commande->id, $livraison1->commande->id);
        $this->assertEquals($livreur->id, $livraison1->livreur->id);
    }

    /** @test */
    public function model_fillable_attributes_are_correct()
    {
        $user = new User();
        $this->assertEquals(['nom', 'prenom', 'email', 'password', 'type'], $user->getFillable());

        $produit = new Produit();
        $this->assertEquals(['nom', 'prix_unitaire', 'categorie', 'description'], $produit->getFillable());

        $facture = new Facturation();
        $this->assertEquals([
            'paiement_id', 'nom', 'prenom', 'ville', 'adresse', 
            'region', 'code_postal', 'email', 'telephone', 'note_commande'
        ], $facture->getFillable());
    }

    /** @test */
    public function factory_creates_valid_models()
    {
        $user = User::factory()->create(['type' => 'client']);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'type' => 'client'
        ]);

        $produit = Produit::factory()->create(['prix_unitaire' => 99.99]);
        $this->assertDatabaseHas('produits', [
            'id' => $produit->id,
            'prix_unitaire' => 99.99
        ]);

        $commande = Commande::factory()->create();
        $this->assertDatabaseHas('commandes', ['id' => $commande->id]);

        $paiement = Paiement::factory()->create(['type' => 'carte']);
        $this->assertDatabaseHas('paiements', [
            'id' => $paiement->id,
            'type' => 'carte'
        ]);
    }
}