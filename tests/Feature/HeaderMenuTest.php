<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeaderMenuTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected function setUp(): void
    {
        putenv('ADMIN_PASSWORD=Test-Password-123');
        parent::setUp();
    }

    public function test_menu_lists_main_pages_and_categories(): void
    {
        $res = $this->get('/')->assertOk();

        foreach (['shop', 'auction.index', 'personal-shopper.index', 'blog.index', 'contact', 'about', 'collections'] as $route) {
            $res->assertSee('href="' . route($route) . '"', false);
        }
        foreach (Category::whereNull('parent_id')->get() as $cat) {
            $res->assertSee(route('shop', ['category' => $cat->slug]), false);
        }
        $res->assertSee('id="mobileDrawer"', false)->assertSee(route('login'), false);
    }

    public function test_new_category_appears_in_menu_immediately(): void
    {
        $this->get('/');   // يملأ الكاش
        Category::create(['name' => 'المخطوطات', 'slug' => 'manuscripts', 'order' => 99]);

        $this->get('/')->assertSee(route('shop', ['category' => 'manuscripts']), false);
    }

    public function test_disabled_services_are_hidden_from_menu(): void
    {
        Setting::set('auction_enabled', '0');

        $this->get('/')->assertDontSee('href="' . route('auction.index') . '" class="nav-link', false);
    }

    public function test_logged_in_menu_has_account_links_and_logout(): void
    {
        $user = User::create(['name' => 'خالد', 'email' => 'k@test.sa', 'password' => 'secret-pass']);

        $this->actingAs($user)->get('/')
            ->assertSee(route('account.orders'), false)
            ->assertSee(route('logout'), false)
            ->assertDontSee(route('admin.dashboard'), false);

        $admin = User::create(['name' => 'مدير', 'email' => 'a@test.sa', 'password' => 'secret-pass', 'role' => 'MANAGER']);
        $this->actingAs($admin)->get('/')->assertSee(route('admin.dashboard'), false);
    }

    public function test_shop_is_active_on_product_page(): void
    {
        Product::create([
            'sku' => 'QQ-M1', 'title' => 'قطعة', 'slug' => 'piece', 'price' => 100, 'stock' => 1,
            'is_active' => true, 'category_id' => Category::first()->id,
        ]);

        $this->get('/product/piece')->assertSee('class="nav-link active" aria-haspopup="true"', false);
    }
}
