<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Client;
use App\Models\Vendeur;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_utilisateur_peut_etre_cree()
    {
        $user = User::factory()->create([
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'jean.dupont@example.com',
            'type' => 'client'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'jean.dupont@example.com',
            'type' => 'client'
        ]);
    }

    /** @test */
    public function un_utilisateur_peut_avoir_un_profil_admin()
    {
        $user = User::factory()->create(['type' => 'admin']);
        $admin = Admin::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Admin::class, $user->admin);
        $this->assertEquals($user->id, $user->admin->user_id);
    }

    /** @test */
    public function un_utilisateur_peut_avoir_un_profil_client()
    {
        $user = User::factory()->create(['type' => 'client']);
        $client = Client::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Client::class, $user->client);
        $this->assertEquals($user->id, $user->client->user_id);
    }

    /** @test */
    public function un_utilisateur_peut_avoir_plusieurs_profils_vendeur()
    {
        $user = User::factory()->create(['type' => 'vendeur']);
        $vendeurs = Vendeur::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertCount(3, $user->vendeurs);
        $this->assertInstanceOf(Vendeur::class, $user->vendeurs->first());
    }

    /** @test */
    public function le_type_dutilisateur_est_valide()
    {
        $user = User::factory()->create(['type' => 'client']);
        
        try {
            $user->type = 'invalid_type';
            $user->save();
            $this->fail("Une exception aurait dû être levée pour un type invalide");
        } catch (\Illuminate\Database\QueryException $e) {
            $this->assertStringContainsString('CHECK constraint failed: type', $e->getMessage());
        }
    }

    /** @test */
    public function le_mot_de_passe_est_crypte()
    {
        $password = 'secret123';
        $user = User::factory()->create(['password' => bcrypt($password)]);
        
        $this->assertNotEquals($password, $user->password);
        $this->assertTrue(\Hash::check($password, $user->password));
    }
}