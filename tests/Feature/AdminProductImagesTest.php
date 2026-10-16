<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductImagesTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private User $admin;

    protected function setUp(): void
    {
        putenv('ADMIN_PASSWORD=Test-Password-123');
        parent::setUp();
        Storage::fake('public');
        $this->admin = User::create(['name' => 'مدير', 'email' => 'm@test.sa', 'password' => 'secret-pass', 'role' => 'MANAGER']);
    }

    public function test_validation_messages_are_arabic(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/products', ['title' => ''])
            ->assertSessionHasErrors(['title' => 'اسم المنتج مطلوب.', 'price' => 'السعر مطلوب.']);
    }

    public function test_admin_layout_has_csrf_token_for_image_actions(): void
    {
        $this->actingAs($this->admin)->get('/admin/products')->assertSee('name="csrf-token"', false);
    }

    public function test_create_product_with_images_then_change_main_and_delete(): void
    {
        $this->actingAs($this->admin)->post('/admin/products', [
            'title' => 'ساعة حائط', 'category_id' => Category::first()->id,
            'price' => 3200, 'stock' => 1, 'is_active' => 1,
            'images' => [
                UploadedFile::fake()->image('a.jpg', 800, 800),
                UploadedFile::fake()->image('b.jpg', 800, 800),
                UploadedFile::fake()->image('c.jpg', 800, 800),
            ],
        ])->assertRedirect(route('admin.products.index'));

        $product = Product::with('images')->first();
        $this->assertCount(3, $product->images);
        Storage::disk('public')->assertExists($product->images[0]->url);

        $third = $product->images[2];
        $this->patchJson("/admin/products/images/{$third->id}/main")->assertJson(['success' => true]);
        $this->assertSame($third->url, $product->fresh()->mainImage());

        $this->deleteJson("/admin/products/images/{$third->id}")->assertJson(['success' => true]);
        Storage::disk('public')->assertMissing($third->url);
        $this->assertCount(2, $product->fresh()->images);
    }

    public function test_non_image_upload_is_rejected_with_arabic_message(): void
    {
        $this->actingAs($this->admin)->post('/admin/products', [
            'title' => 'قطعة', 'category_id' => Category::first()->id, 'price' => 100, 'stock' => 1,
            'images' => [UploadedFile::fake()->create('virus.pdf', 10, 'application/pdf')],
        ])->assertSessionHasErrors(['images.0' => 'أحد الملفات المرفوعة ليس صورة.']);
    }
}
