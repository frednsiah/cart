<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Storage;
use Carbon\Carbon;
use App\Models\Product;

class AddProductsTest extends TestCase
{
	function setUp(): void {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->faker = \Faker\Factory::create();
	}
    public function testCreateProduct() {
        $postData = $this->postData();
        
        $response = $this
            ->actingAs($this->user)
            ->call('POST', route('create'), $postData);

            $response
            ->assertStatus(200)
			->assertJson([
                'success' => true,
                'data' => $postData
			]);
    }

    public function testCreateProductDatabase() {
        $postData = $this->postData();

        $response = $this
            ->actingAs($this->user)
            ->call('POST', route('create'), $postData);

            $this->assertDatabaseHas('products', $postData);
    }
    public function testCreateProductJsonFileExistsAndValid() {
        $postData = $this->postData();

        $response = $this
            ->actingAs($this->user)
            ->call('POST', route('create'), $postData);

        $product = $response->decodeResponseJson();

        $created_at = Carbon::parse($product['data']['created_at']);

        $file_path = "products/{$product['data']['id']}-{$created_at->toDateString()}.json";
        $response->assertStatus(200);
        $this->assertTrue(Storage::disk('local')->exists($file_path));
        $this->assertJsonStringEqualsJsonFile(
            storage_path('app/' . $file_path), json_encode(['product' => $product['data']])
        );
    }

    public function testEditProduct() {
        $product = Product::inRandomOrder()->first();

        $postData = $this->postData();
        
        $response = $this
            ->actingAs($this->user)
            ->call('PUT', route('edit', $product->id), $postData);

            $response
            ->assertStatus(200)
			->assertJson([
                'success' => true,
                'data' => $postData
			]);
    }

    private function postData() {
        return [
            'name' => $this->faker->word(),
            'quantity' => $this->faker->numberBetween(1, 100),
            'price' => $this->faker->randomFloat(2, 1, 50)
        ];
    }
}
