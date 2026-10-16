<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\AuctionItem;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * اختبارات دخان: كل صفحات الموقع ولوحة الإدارة تعمل، ومسار الشراء سليم
 */
class SiteSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private Product $product;

    protected function setUp(): void
    {
        putenv('ADMIN_PASSWORD=Test-Password-123');
        parent::setUp();

        $this->product = Product::create([
            'sku' => 'QQ-TEST01', 'title' => 'ساعة جدارية فرنسية', 'slug' => 'french-clock',
            'price' => 1000, 'stock' => 1, 'is_active' => true, 'is_featured' => true,
            'is_rare' => true, 'category_id' => Category::first()->id,
        ]);
    }

    private function user(string $role = 'CUSTOMER'): User
    {
        return User::create([
            'name' => "مستخدم {$role}", 'email' => strtolower($role) . uniqid() . '@test.sa',
            'password' => 'secret-pass', 'role' => $role, 'is_active' => true,
        ]);
    }

    public function test_public_pages_load(): void
    {
        AuctionItem::create([
            'product_id' => $this->product->id, 'estimated_price' => 5000,
            'deadline' => now()->addWeek(), 'is_active' => true,
        ]);

        foreach ([
            '/', '/shop', '/shop?q=ساعة', '/product/french-clock', '/auction', '/personal-shopper',
            '/blog', '/blog/' . urlencode('تاريخ-الأثاث-الفيكتوري'), '/about', '/contact', '/services',
            '/shipping', '/returns', '/faq', '/collections', '/login', '/register',
        ] as $url) {
            $this->get($url)->assertOk();
        }

        $this->get('/no-such-page')->assertNotFound();
    }

    public function test_contact_form_saves_message(): void
    {
        $this->post('/contact', [
            'name' => 'خالد', 'email' => 'k@test.sa', 'subject' => 'استفسار',
            'message' => 'هل الساعة الفرنسية ما زالت متوفرة؟',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertSame(1, ContactMessage::count());
    }

    public function test_customer_purchase_flow_and_stock_protection(): void
    {
        $customer = $this->user();
        $address  = Address::create([
            'user_id' => $customer->id, 'full_name' => 'خالد', 'phone' => '0500000000',
            'country' => 'السعودية', 'city' => 'الرياض', 'is_default' => true,
        ]);

        $this->actingAs($customer);
        foreach (['/account/profile', '/account/orders', '/account/wishlist', '/account/addresses'] as $url) {
            $this->get($url)->assertOk();
        }

        $this->postJson('/cart', ['product_id' => $this->product->id])->assertJson(['success' => true]);
        $this->get('/cart')->assertOk();
        $this->get('/checkout')->assertOk();

        // دفع إلكتروني غير مفعّل → مرفوض
        $this->post('/checkout', ['address_id' => $address->id, 'payment_method' => 'online'])
            ->assertSessionHasErrors('payment_method');

        $this->post('/checkout', ['address_id' => $address->id, 'payment_method' => 'cod'])
            ->assertRedirect(route('account.orders'));

        $this->assertSame(0, $this->product->fresh()->stock);
        $this->assertEquals(1150, Order::first()->total); // 1000 + 15% ضريبة، شحن مجاني

        // عميل آخر أضاف نفس القطعة قبل نفادها → لا يمكنه شراؤها
        $other = $this->user();
        $otherAddress = Address::create([
            'user_id' => $other->id, 'full_name' => 'سارة', 'phone' => '0511111111',
            'country' => 'السعودية', 'city' => 'جدة', 'is_default' => true,
        ]);
        $other->cartItems()->create(['product_id' => $this->product->id, 'quantity' => 1]);

        $this->actingAs($other)
            ->post('/checkout', ['address_id' => $otherAddress->id, 'payment_method' => 'cod'])
            ->assertRedirect(route('cart.index'))
            ->assertSessionHas('error');

        $this->assertSame(1, Order::count());
        $this->assertSame(0, $this->product->fresh()->stock);
    }

    public function test_coupon_discount_applies_before_vat(): void
    {
        Coupon::create(['code' => 'WELCOME', 'type' => 'PERCENTAGE', 'value' => 10, 'is_active' => true]);
        $customer = $this->user();
        $customer->cartItems()->create(['product_id' => $this->product->id, 'quantity' => 1]);

        // (1000 - 100) + 15% = 1035
        $this->actingAs($customer)->postJson('/coupon/validate', ['code' => 'welcome'])
            ->assertJson(['valid' => true, 'discount' => '100', 'new_total' => '1,035']);
    }

    public function test_wishlist_rejects_unknown_product(): void
    {
        $this->actingAs($this->user())->postJson('/wishlist/999999')->assertNotFound();
    }

    public function test_admin_pages_load(): void
    {
        $customer = $this->user();
        $this->actingAs($this->user('SUPER_ADMIN'));

        foreach ([
            '/admin', '/admin/products', '/admin/products/create',
            "/admin/products/{$this->product->id}/edit", '/admin/categories', '/admin/orders',
            '/admin/customers', "/admin/customers/{$customer->id}", '/admin/coupons',
            '/admin/auction-interest', '/admin/personal-shopper', '/admin/messages', '/admin/settings',
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_customer_search_does_not_leak_admin_accounts(): void
    {
        $admin = $this->user('SUPER_ADMIN');
        $this->actingAs($admin)
            ->get('/admin/customers?q=' . urlencode('مستخدم'))
            ->assertOk()
            ->assertDontSee($admin->email);
    }

    public function test_staff_cannot_reach_manager_pages(): void
    {
        $this->actingAs($this->user('STAFF'));

        $this->get('/admin/products')->assertOk();
        $this->get('/admin/settings')->assertForbidden();
        $this->get('/admin/coupons')->assertForbidden();
        $this->delete("/admin/products/{$this->product->id}")->assertForbidden();
    }

    public function test_cancelling_an_order_returns_piece_to_stock(): void
    {
        $customer = $this->user();
        $order = Order::create([
            'user_id' => $customer->id, 'subtotal' => 1000, 'total' => 1150, 'status' => 'PENDING',
        ]);
        $order->items()->create([
            'product_id' => $this->product->id, 'title' => $this->product->title, 'price' => 1000, 'quantity' => 1,
        ]);
        $this->product->update(['stock' => 0]);

        $this->actingAs($this->user('MANAGER'))
            ->patch("/admin/orders/{$order->id}", ['status' => 'CANCELLED'])
            ->assertRedirect();

        $this->assertSame(1, $this->product->fresh()->stock);
    }

    public function test_admin_seeder_refuses_missing_password(): void
    {
        $this->assertDatabaseHas('users', ['role' => 'SUPER_ADMIN']);

        putenv('ADMIN_PASSWORD=');
        putenv('ADMIN_EMAIL=other@test.sa');
        $this->artisan('db:seed', ['--class' => 'AdminSeeder']);
        putenv('ADMIN_EMAIL');

        $this->assertDatabaseMissing('users', ['email' => 'other@test.sa']);
    }
}
