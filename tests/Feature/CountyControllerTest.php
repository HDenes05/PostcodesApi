<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use App\Models\County;
use App\Models\User;  // azokhoz a végpontok, amelyeknél felhasználó hitelesítés kell
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class CountyControllerTest extends TestCase
{
    use RefreshDatabase, HasApiTokens, HasFactory, Notifiable;
    
    public function test_index_returns_all_counties()
    {
        County::factory()->create(['name' => 'Pest']);
        County::factory()->create(['name' => 'Baranya']);

        $response = $this->getJson('/api/counties');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Pest'])
            ->assertJsonFragment(['name' => 'Baranya']);
    } 

    public function test_index_filters_by_needle()
    {
        County::factory()->create(['name' => 'Bács-Kiskun']);
        County::factory()->create(['name' => 'Baranya']);

        $response = $this->getJson('/api/counties?needle=bar');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Baranya'])  // Baranya benne van a válaszban
            ->assertJsonMissing(['name' => 'Bács-Kis']);  // Bács-Kiskun nincs benne
    } 

    public function test_store_creates_new_county()
    {
        // Létrehozunk egy felhasználót
		$user = User::factory()->create();
		// Lekérjük a tokent
        $token = $user->createToken('TestToken')->plainTextToken;

		// A Header-ben elküldjük a tokent és meghívjuk a végpontot (postJson) a szükséges adatokkal
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/counties', [
            'name' => 'Somogy'
        ]);

		// teszteljük, hogy 200-as kódot kapunk-e és a válaszban benne van-e az újonnan hozzáadott adat.
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Somogy']);
		
		// teszteljük, hogy az adatbázisban is ott van-e at adat
        $this->assertDatabaseHas('counties', ['name' => 'Somogy']);
    }

    public function test_update_modifies_existing_county()
    {
        $county = County::factory()->create(['name' => 'Heves']);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/counties/{$county->id}", [
            'name' => 'Nógrád'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Nógrád']);

        $this->assertDatabaseHas('counties', ['id' => $county->id, 'name' => 'Nógrád']);
    } 

    public function test_update_returns_404_for_missing_county()
    {
        $county = County::factory()->create(['name' => 'Tolna']);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/counties/{$county->id}", [
            'name' => 'valami'
        ]);

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Not found!']);
    } 

    public function test_delete_removes_county()
    {
        $county = County::factory()->create(['name' => 'Vas']);

        $response = $this->deleteJson("/api/counties/{$county->id}");

        $response->assertStatus(410)
            ->assertJsonFragment(['message' => 'Deleted']);

        $this->assertDatabaseMissing('counties', ['id' => $county->id]);
    } 
}
