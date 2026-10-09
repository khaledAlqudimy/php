<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\NewOrderForAdmin;
use App\Notifications\OrderPlaced;
use App\Notifications\OrderStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private User $customer;
    private Address $address;
    private Product $product;

    protected function setUp(): void
    {
        putenv('ADMIN_PASSWORD=Test-Password-123');
        parent::setUp();

        Notification::fake();
        Setting::set('site_email', 'orders@test.sa');

        $this->customer = User::create([
            'name' => 'خالد', 'email' => 'k@test.sa', 'password' => 'secret-pass', 'role' => 'CUSTOMER',
        ]);
        $this->address = Address::create([
            'user_id' => $this->customer->id, 'full_name' => 'خالد', 'phone' => '0500000000',
            'country' => 'السعودية', 'city' => 'الرياض', 'is_default' => true,
        ]);
        $this->product = Product::create([
            'sku' => 'QQ-PAY001', 'title' => 'مزهرية نحاسية', 'slug' => 'brass-vase',
            'price' => 1000, 'stock' => 1, 'is_active' => true, 'category_id' => Category::first()->id,
        ]);
        $this->customer->cartItems()->create(['product_id' => $this->product->id, 'quantity' => 1]);
    }

    private function enableMoyasar(): void
    {
        config(['services.moyasar.secret_key' => 'sk_test_fake']);
    }

    private function fakeInvoice(string $status, int $amount = 115000): void
    {
        Http::fake([
            'api.moyasar.com/v1/invoices' => Http::response([
                'id' => 'inv_123', 'status' => 'initiated', 'amount' => $amount,
                'currency' => 'SAR', 'url' => 'https://checkout.moyasar.com/invoices/inv_123',
            ]),
            'api.moyasar.com/v1/invoices/inv_123' => Http::response([
                'id' => 'inv_123', 'status' => $status, 'amount' => $amount, 'currency' => 'SAR',
            ]),
        ]);
    }

    private function placeOnlineOrder()
    {
        return $this->actingAs($this->customer)->post('/checkout', [
            'address_id' => $this->address->id, 'payment_method' => 'online',
        ]);
    }

    public function test_online_option_hidden_without_keys(): void
    {
        $this->actingAs($this->customer)->get('/checkout')->assertOk()->assertDontSee('value="online"', false);
        $this->placeOnlineOrder()->assertSessionHasErrors('payment_method');
    }

    public function test_successful_online_payment(): void
    {
        $this->enableMoyasar();
        $this->fakeInvoice('paid');

        $this->actingAs($this->customer)->get('/checkout')->assertSee('value="online"', false);

        $order = null;
        $this->placeOnlineOrder()->assertRedirect();
        $order = Order::first();
        $this->assertSame(0, $this->product->fresh()->stock);   // القطعة محجوزة

        // لا إيميلات قبل الدفع
        Notification::assertNothingSent();

        $this->get(route('payments.pay', $order))
            ->assertRedirect('https://checkout.moyasar.com/invoices/inv_123');

        Http::assertSent(fn ($r) => $r->url() === 'https://api.moyasar.com/v1/invoices'
            && $r['amount'] === 115000 && $r['currency'] === 'SAR');

        $this->get(route('payments.return', $order))
            ->assertRedirect(route('account.orders'))
            ->assertSessionHas('success');

        $order->refresh();
        $this->assertSame('paid', $order->payment_status);
        $this->assertSame('PROCESSING', $order->status);
        $this->assertNotNull($order->paid_at);

        Notification::assertSentTo($this->customer, OrderPlaced::class);
        Notification::assertSentOnDemand(NewOrderForAdmin::class);

        // إشعار Moyasar بعدها لا يكرر الإيميلات
        $this->post('/payments/moyasar/callback', ['id' => 'inv_123'])->assertOk();
        Notification::assertSentToTimes($this->customer, OrderPlaced::class, 1);
    }

    public function test_unpaid_return_does_not_mark_paid(): void
    {
        $this->enableMoyasar();
        $this->fakeInvoice('initiated');

        $this->placeOnlineOrder();
        $order = Order::first();
        $this->get(route('payments.pay', $order));

        $this->get(route('payments.return', $order))->assertSessionHas('error');
        $this->assertSame('unpaid', $order->fresh()->payment_status);
    }

    public function test_amount_mismatch_is_rejected(): void
    {
        $this->enableMoyasar();
        $this->fakeInvoice('paid', 100);   // مبلغ مختلف عن الطلب

        $this->placeOnlineOrder();
        $order = Order::first();
        $this->get(route('payments.pay', $order));
        $this->get(route('payments.return', $order));

        $this->assertSame('unpaid', $order->fresh()->payment_status);
    }

    public function test_callback_marks_order_paid_without_login(): void
    {
        $this->enableMoyasar();
        $this->fakeInvoice('paid');

        $this->placeOnlineOrder();
        $order = Order::first();
        $order->update(['payment_reference' => 'inv_123']);
        auth()->logout();

        $this->post('/payments/moyasar/callback', ['id' => 'pay_999', 'invoice_id' => 'inv_123'])->assertOk();
        $this->assertSame('paid', $order->fresh()->payment_status);
    }

    public function test_other_customers_cannot_pay_or_view_order(): void
    {
        $this->enableMoyasar();
        $this->fakeInvoice('initiated');
        $this->placeOnlineOrder();
        $order = Order::first();

        $other = User::create(['name' => 'آخر', 'email' => 'o@test.sa', 'password' => 'secret-pass', 'role' => 'CUSTOMER']);
        $this->actingAs($other)->get(route('payments.pay', $order))->assertNotFound();
    }

    public function test_abandoned_online_order_is_cancelled_and_stock_released(): void
    {
        $this->enableMoyasar();
        $this->fakeInvoice('initiated');

        $this->placeOnlineOrder();
        $order = Order::first();
        $order->update(['payment_reference' => 'inv_123']);
        $order->forceFill(['created_at' => now()->subHours(2)])->save();

        $this->artisan('orders:expire-unpaid')->assertSuccessful();

        $this->assertSame('CANCELLED', $order->fresh()->status);
        $this->assertSame(1, $this->product->fresh()->stock);
    }

    public function test_expiry_keeps_order_that_was_actually_paid(): void
    {
        $this->enableMoyasar();
        $this->fakeInvoice('paid');

        $this->placeOnlineOrder();
        $order = Order::first();
        $order->update(['payment_reference' => 'inv_123']);
        $order->forceFill(['created_at' => now()->subHours(2)])->save();

        $this->artisan('orders:expire-unpaid')->assertSuccessful();

        $this->assertSame('paid', $order->fresh()->payment_status);
        $this->assertSame('PROCESSING', $order->fresh()->status);
        $this->assertSame(0, $this->product->fresh()->stock);
    }

    public function test_cod_order_sends_confirmation_and_status_emails(): void
    {
        Setting::set('bank_iban', 'SA0380000000608010167519');

        $this->actingAs($this->customer)->post('/checkout', [
            'address_id' => $this->address->id, 'payment_method' => 'bank_transfer',
        ])->assertRedirect(route('account.orders'));

        $order = Order::first();
        Notification::assertSentTo($this->customer, OrderPlaced::class, function ($n) {
            $text = collect($n->toMail($this->customer)->introLines)->implode("\n");
            return str_contains($text, 'SA0380000000608010167519');
        });

        $manager = User::create(['name' => 'مدير', 'email' => 'm@test.sa', 'password' => 'secret-pass', 'role' => 'MANAGER']);
        $this->actingAs($manager)->patch("/admin/orders/{$order->id}", ["status" => "SHIPPED"])->assertSessionHasNoErrors()->assertRedirect();
        $this->assertSame("SHIPPED", $order->fresh()->status);

        Notification::assertSentTo($this->customer, OrderStatusChanged::class);
    }
}
