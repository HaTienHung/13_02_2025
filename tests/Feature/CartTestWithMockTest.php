<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Cart\CartService;
use Mockery;
use Tests\TestCase;

class CartTestWithMockTest extends TestCase
{
    private $cartServiceMock;
    private $user;

    public function test_user_can_get_cart_items()
    {
        // ✅ Giả lập dữ liệu giỏ hàng
        $mockCartItems = [
            ['product_id' => 1, 'quantity' => 2],
            ['product_id' => 2, 'quantity' => 1],
        ];

        // ✅ Khi gọi `getCart`, nó sẽ trả về `$mockCartItems`
        $this->cartServiceMock
            ->shouldReceive('getCart')
            ->with($this->user->id)
            ->once()
            ->andReturn($mockCartItems);

        // ✅ Gửi request với user đã đăng nhập
        $response = $this->actingAs($this->user)->getJson('/api/app/cart');

        // ✅ Kiểm tra phản hồi HTTP 200
        $response->assertStatus(200);

        // $response->dump();

        // ✅ Kiểm tra dữ liệu trả về
        $response->assertJson(['cart' => $mockCartItems]);
    }

    public function test_guest_cannot_access_cart()
    {
        // ✅ Gửi request mà không đăng nhập
        $response = $this->getJson('/api/app/cart');

        // ✅ Kiểm tra lỗi 401 Unauthorized
        $response->assertStatus(401);
    }

    public function test_user_can_update_cart()
    {
        // ✅ 1. Giả lập giỏ hàng hiện có của user (trước khi update)
        $mockCartItems = [
            ['product_id' => 4, 'quantity' => 1],
        ];

        // ✅ 2. Giả lập request update giỏ hàng
        $updateRequest = [
            'product_id' => 2,
            'quantity' => 3
        ];

        // ✅ 3. Mock hành vi của CartService
        $this->cartServiceMock
            ->shouldReceive('updateCartItem')
            ->with($this->user->id, $updateRequest['product_id'], $updateRequest['quantity'])
            ->once()
            ->andReturn(true); // Trả về true nếu cập nhật thành công

        // ✅ 5. Gửi request cập nhật giỏ hàng
        $response = $this->actingAs($this->user)->putJson('/api/app/cart/update', $updateRequest);

        // ✅ 6. Kiểm tra phản hồi HTTP 200
        $response->assertStatus(200);

        $response->dump();
        // ✅ 8. Kiểm tra xem service được gọi đúng số lần
        $this->cartServiceMock->shouldHaveReceived('updateCartItem')->once();
        $this->cartServiceMock->shouldNotHaveReceived('getCart'); // ❌ Không được gọi
    }

    protected function setUp(): void
    {
        parent::setUp();

        // ✅ Tạo user giả lập
        $this->user = User::factory()->create();

        // ✅ Mock CartService
        $this->cartServiceMock = Mockery::mock(CartService::class);

        // ✅ Bind CartService vào container của Laravel
        $this->app->instance(CartService::class, $this->cartServiceMock);
    }

    protected function tearDown(): void
    {
        Mockery::close(); // ✅ Đóng mock tránh lỗi
        parent::tearDown();
    }
}
