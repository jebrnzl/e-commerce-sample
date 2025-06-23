<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;

class ProductWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_page_displays_products()
    {
        $products = Product::factory()->count(2)->create();
        $response = $this->get('/products');
        $response->assertStatus(200);
        foreach ($products as $product) {
            $response->assertSee($product->name);
        }
    }

    public function test_create_page_is_accessible()
    {
        $response = $this->get('/products/create');
        $response->assertStatus(200)
                 ->assertSee('Create Product');
    }

    public function test_can_store_product_via_form()
    {
        $data = ['name' => 'Web Product', 'details' => 'Web details'];
        $response = $this->post('/products', $data);
        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products', $data);
    }

    public function test_edit_page_is_accessible()
    {
        $product = Product::factory()->create();
        $response = $this->get("/products/{$product->id}/edit");
        $response->assertStatus(200)
                 ->assertSee('Edit Product');
    }

    public function test_can_update_product_via_form()
    {
        $product = Product::factory()->create();
        $data = ['name' => 'Changed', 'details' => 'Changed details'];
        $response = $this->put("/products/{$product->id}", $data);
        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products', $data);
    }

    public function test_can_delete_product_via_form()
    {
        $product = Product::factory()->create();
        $response = $this->delete("/products/{$product->id}");
        $response->assertRedirect('/products');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
