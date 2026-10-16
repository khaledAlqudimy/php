<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SeoAndAccountTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected function setUp(): void
    {
        putenv('ADMIN_PASSWORD=Test-Password-123');
        parent::setUp();
    }

    private function product(): Product
    {
        return Product::create([
            'sku' => 'QQ-SEO001', 'title' => 'ثريا كريستال', 'slug' => 'crystal-chandelier',
            'short_desc' => 'ثريا فرنسية من القرن التاسع عشر', 'price' => 4500, 'stock' => 1,
            'is_active' => true, 'category_id' => Category::first()->id,
        ]);
    }

    public function test_pages_have_meta_description_and_canonical(): void
    {
        $this->get('/shop')
            ->assertOk()
            ->assertSee('<meta name="description" content="تسوّق قطع الأنتيك', false)
            ->assertSee('<link rel="canonical" href="' . url('/shop') . '">', false);
    }

    public function test_private_pages_are_noindex(): void
    {
        $this->get('/login')->assertSee('<meta name="robots" content="noindex, nofollow">', false);
        $this->get('/shop')->assertDontSee('noindex');
    }

    public function test_product_page_has_structured_data(): void
    {
        $this->product();

        $this->get('/product/crystal-chandelier')
            ->assertOk()
            ->assertSee('"@type":"Product"', false)
            ->assertSee('"priceCurrency":"SAR"', false)
            ->assertSee('"price":"4500.00"', false)
            ->assertSee('<meta name="description" content="ثريا فرنسية من القرن التاسع عشر">', false);
    }

    public function test_sitemap_lists_products_and_robots_points_to_it(): void
    {
        $this->product();

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee(route('product.show', 'crystal-chandelier'), false)
            ->assertSee(route('privacy'), false);

        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Disallow: /admin')
            ->assertSee('Sitemap: ' . route('sitemap'));
    }

    public function test_legal_pages_load_and_are_linked_in_footer(): void
    {
        $this->get('/privacy')->assertOk()->assertSee('نظام حماية البيانات الشخصية');
        $this->get('/terms')->assertOk()->assertSee('نظام التجارة الإلكترونية');
        $this->get('/')->assertSee(route('privacy'), false)->assertSee(route('terms'), false);
    }

    public function test_placeholder_contact_details_are_hidden(): void
    {
        Setting::set('site_phone', '+966 XX XXX XXXX');
        Setting::set('site_whatsapp', '+966XXXXXXXXX');

        $this->get('/contact')->assertOk()->assertDontSee('XX XXX')->assertDontSee('wa.me');

        Setting::set('site_whatsapp', '+966 55 123 4567');

        $this->get('/contact')->assertSee('https://wa.me/966551234567', false);
        $this->get('/')->assertSee('wa-float', false);
    }

    public function test_password_reset_flow(): void
    {
        Notification::fake();

        $user = User::create([
            'name' => 'خالد', 'email' => 'khaled@test.sa', 'password' => 'old-password', 'role' => 'CUSTOMER',
        ]);

        $this->get('/login')->assertSee(route('password.request'), false);
        $this->get('/forgot-password')->assertOk();

        // بريد غير مسجل → نفس الرسالة (لا نكشف وجود الحساب)
        $this->post('/forgot-password', ['email' => 'nobody@test.sa'])->assertSessionHas('status');

        $this->post('/forgot-password', ['email' => 'khaled@test.sa'])->assertSessionHas('status');

        $token = null;
        Notification::assertSentTo($user, ResetPassword::class, function ($n) use (&$token, $user) {
            $token = $n->token;
            $mail  = $n->toMail($user);
            return $mail->subject === 'إعادة تعيين كلمة المرور — قطعة وقصة';
        });

        $this->get("/reset-password/{$token}?email=khaled@test.sa")->assertOk();

        $this->post('/reset-password', [
            'token' => $token, 'email' => 'khaled@test.sa',
            'password' => 'new-password-1', 'password_confirmation' => 'new-password-1',
        ])->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('new-password-1', $user->fresh()->password));

        // الرابط يُستخدم مرة واحدة فقط
        $this->post('/reset-password', [
            'token' => $token, 'email' => 'khaled@test.sa',
            'password' => 'another-pass-2', 'password_confirmation' => 'another-pass-2',
        ])->assertSessionHasErrors('email');
    }
}
