<?php

// tests/Feature/OrderProcessTest.php
namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\InventoryTransaction;
use App\Models\OrderItem;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderProcessTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_place_order_successfully()
    {
        // ✅ Tạo user và sản phẩm
        $user = User::factory()->create();

        $token = $user->createToken('TestToken')->plainTextToken;

        // dump("Generated Token: " . $token);

        $this->seed(CategorySeeder::class);

        // Lấy category đầu tiên
        $category = Category::first();

        $product = Product::create([
            'name' => 'Orange',
            'price' => 7,
            'category_id' => $category->id
        ]);

        // ✅ Giả lập tồn kho (inventory)
        InventoryTransaction::create([
            'product_id' => $product->id,
            'quantity' => 10, // Sản phẩm có 10 cái trong kho
            'type' => 'import' // Nhập hàng
        ]);

        // ✅ Giả lập user đăng nhập
        $this->actingAs($user);

        // Gửi request với token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/app/orders/create', [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2]
            ]
        ]);
        // dump($response->json());
        // ✅ Kiểm tra HTTP response
        $response->assertStatus(201)
            ->assertJsonStructure(['message']);

        // ✅ Kiểm tra đơn hàng đã được tạo trong DB
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total_price' => 14
        ]);

        // ✅ Kiểm tra sản phẩm đã được thêm vào OrderItem
        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 7
        ]);

        // ✅ Kiểm tra tồn kho đã giảm
        $this->assertDatabaseHas('inventory_transactions', [
            'product_id' => $product->id,
            'quantity' => 2, // Đã trừ 2 cái
            'type' => 'export'
        ]);
    }

    public function test_user_cannot_order_if_stock_not_enough()
    {
        // ❌ Test đặt hàng khi kho không đủ hàng

        // ✅ Tạo user và sản phẩm
        $user = User::factory()->create();


        $this->seed(CategorySeeder::class);

        // Lấy category đầu tiên
        $category = Category::first();

        $product = Product::create([
            'name' => 'Orange',
            'price' => 7,
            'category_id' => $category->id
        ]);

        // ✅ Giả lập tồn kho chỉ còn 1 cái
        InventoryTransaction::create([
            'product_id' => $product->id,
            'quantity' => 1, // Chỉ có 1 cái trong kho
            'type' => 'import'
        ]);

        // ✅ Giả lập user đăng nhập
        $this->actingAs($user);

        // ✅ Gửi request đặt hàng với số lượng **lớn hơn tồn kho**
        $response = $this->postJson('/api/app/orders/create', [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 5] // Yêu cầu 5 cái nhưng chỉ còn 1
            ]
        ]);
        $response->dump();

        // ✅ Kiểm tra HTTP response 422
        $response->assertStatus(422)
            ->assertJson(['message' => "Sản phẩm {$product->name} không đủ hàng trong kho."]);

        // ✅ Kiểm tra **không có đơn hàng nào được tạo**
        $this->assertDatabaseMissing('orders', [
            'user_id' => $user->id
        ]);

        // ✅ Kiểm tra **tồn kho không bị trừ**
        $this->assertDatabaseHas('inventory_transactions', [
            'product_id' => $product->id,
            'quantity' => 1
        ]);
    }
}
