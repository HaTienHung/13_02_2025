<?php


namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\CartItem;  // Giả sử bạn có model Cart
use App\Models\Category;
use App\Models\Product;
use Database\Seeders\CategorySeeder;

class CartTest extends TestCase
{
    use RefreshDatabase;


    public function test_it_can_fetch_the_cart_of_an_authenticated_user()
    {
        // Tạo một user
        $user = User::factory()->create();

        $token = $user->createToken('TestToken')->plainTextToken;

        $this->seed(CategorySeeder::class);

        // Lấy category đầu tiên
        $category = Category::first();

        $product = Product::create([
            'name' => 'Orange',
            'price' => 7,
            'category_id' => $category->id
        ]);


        // Tạo giỏ hàng cho người dùng
        $cart = CartItem::create([
            'user_id' => $product->id,
            'product_id' => 1,
            'quantity' => 2,
        ]);
        $this->actingAs($user);
        // Đăng nhập với người dùng đã tạo
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/app/cart');

        // Kiểm tra response trả về có chứa giỏ hàng của người dùng
        $response->assertStatus(200);
        $response->assertJson([
            'cart' => [
                [
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                ],
            ],
        ]);
    }
    public function test_user_can_add_item_into_cart()
    {

        $user = User::factory()->create();

        $token = $user->createToken('TestToken')->plainTextToken;

        $this->seed(CategorySeeder::class);

        // Lấy category đầu tiên
        $category = Category::first();

        $product = Product::create([
            'name' => 'Orange',
            'price' => 7,
            'category_id' => $category->id
        ]);
        // // Tạo giỏ hàng cho người dùng
        // $cart = CartItem::create([
        //     'user_id' => $product->id,
        //     'product_id' => 1,
        //     'quantity' => 2,
        // ]);
        $this->actingAs($user);
        // Đăng nhập với người dùng đã tạo
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/app/cart/store', [
            ['product_id' => $product->id, 'quantity' => 2]
        ]);

        // Kiểm tra response trả về có chứa giỏ hàng của người dùng
        $response->assertStatus(201)
            ->assertJsonStructure(['message']);
        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2
        ]);
    }
}
