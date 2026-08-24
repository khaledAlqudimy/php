<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'الأثاث الكلاسيكي',
                'slug'        => 'classic-furniture',
                'description' => 'قطع أثاث أصيلة من حقب زمنية مختلفة',
                'order'       => 1,
            ],
            [
                'name'        => 'التحف والمقتنيات',
                'slug'        => 'antiques-collectibles',
                'description' => 'تحف نادرة وقطع للمقتنين',
                'order'       => 2,
            ],
            [
                'name'        => 'الساعات الكلاسيكية',
                'slug'        => 'vintage-clocks',
                'description' => 'ساعات حائط وطاولة من الحقبة الذهبية',
                'order'       => 3,
            ],
            [
                'name'        => 'الإضاءة والنجف',
                'slug'        => 'lighting-chandeliers',
                'description' => 'ثريات ومصابيح كلاسيكية فاخرة',
                'order'       => 4,
            ],
            [
                'name'        => 'الفضيات والنحاسيات',
                'slug'        => 'silver-brass',
                'description' => 'آنية وتحف من الفضة والنحاس',
                'order'       => 5,
            ],
            [
                'name'        => 'اللوحات والفنون',
                'slug'        => 'paintings-art',
                'description' => 'لوحات فنية وأعمال تراثية',
                'order'       => 6,
            ],
        ];

        foreach ($categories as $data) {
            Category::firstOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        $this->command->info('✅ تم إنشاء ' . count($categories) . ' تصنيفات');
    }
}
