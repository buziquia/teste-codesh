<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    // Teste para GET /
    public function test_api_details()
    {
        $response = $this->getJson('/api');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'database_connection',
                     'last_cron_run',
                     'uptime',
                     'memory_usage'
                 ]);
    }

    // Teste para GET /products
    public function test_list_all_products()
    {
        Product::factory()->count(15)->create(); // Cria 15 produtos fictícios

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
                 ->assertJsonStructure(['data', 'links', 'meta']);
    }

    // Teste para GET /products/:code
    public function test_get_product_by_code()
    {
        $product = Product::factory()->create(['code' => '12345']);

        $response = $this->getJson('/api/products/12345');

        $response->assertStatus(200)
                 ->assertJsonFragment(['code' => '12345']);
    }

    // Teste para PUT /products/:code
    public function test_update_product()
    {
        $product = Product::factory()->create(['code' => '12345']);
        $updateData = [
            'name' => 'Updated Product Name',
            'price' => 99.99
        ];

        $response = $this->putJson('/api/products/12345', $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Product Name']);
    }

    // Teste para DELETE /products/:code
    public function test_delete_product()
    {
        $product = Product::factory()->create(['code' => '12345']);

        $response = $this->deleteJson('/api/products/12345');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Product status changed to trash']);
    }
}

