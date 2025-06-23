<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products()
    {
        Product::factory()->count(3)->create();
        $response = $this->getJson('/api/products');
        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_can_create_a_product()
    {
        $data = ['name' => 'Test', 'details' => 'Test details'];
        $response = $this->postJson('/api/products', $data);
        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Test', 'details' => 'Test details']);
        $this->assertDatabaseHas('products', $data);
    }

    public function test_can_show_a_product()
    {
        $product = Product::factory()->create();
        $response = $this->getJson("/api/products/{$product->id}");
        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'name' => $product->name,
                     'details' => $product->details,
                 ]);
    }

    public function test_can_update_a_product()
    {
        $product = Product::factory()->create();
        $data = ['name' => 'Updated', 'details' => 'Updated details'];
        $response = $this->putJson("/api/products/{$product->id}", $data);
        $response->assertStatus(200)
                 ->assertJsonFragment($data);
        $this->assertDatabaseHas('products', $data);
    }

    public function test_can_delete_a_product()
    {
        $product = Product::factory()->create();
        $response = $this->deleteJson("/api/products/{$product->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
