<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    // ══════════════════════════════════════════
    //  robots.txt — يُولَّد ديناميكياً ليحمل رابط خريطة الموقع الصحيح
    // ══════════════════════════════════════════
    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /account',
            'Disallow: /cart',
            'Disallow: /checkout',
            'Disallow: /login',
            'Disallow: /register',
            'Disallow: /forgot-password',
            'Disallow: /reset-password',
            '',
            'Sitemap: ' . route('sitemap'),
        ];

        return response(implode("\n", $lines) . "\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    // ══════════════════════════════════════════
    //  sitemap.xml
    // ══════════════════════════════════════════
    public function sitemap(BlogController $blog): Response
    {
        $urls = collect([
            ['loc' => route('home'),                   'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => route('shop'),                   'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => route('auction.index'),          'priority' => '0.8', 'changefreq' => 'daily'],
            ['loc' => route('personal-shopper.index'), 'priority' => '0.6'],
            ['loc' => route('collections'),            'priority' => '0.6'],
            ['loc' => route('blog.index'),             'priority' => '0.6', 'changefreq' => 'weekly'],
            ['loc' => route('about'),                  'priority' => '0.4'],
            ['loc' => route('services'),               'priority' => '0.4'],
            ['loc' => route('contact'),                'priority' => '0.4'],
            ['loc' => route('shipping'),               'priority' => '0.3'],
            ['loc' => route('returns'),                'priority' => '0.3'],
            ['loc' => route('faq'),                    'priority' => '0.3'],
            ['loc' => route('privacy'),                'priority' => '0.2'],
            ['loc' => route('terms'),                  'priority' => '0.2'],
        ]);

        Category::orderBy('order')->get(['slug'])->each(fn ($c) => $urls->push([
            'loc' => route('shop', ['category' => $c->slug]), 'priority' => '0.7', 'changefreq' => 'weekly',
        ]));

        Product::active()->orderByDesc('updated_at')->get(['slug', 'updated_at'])->each(fn ($p) => $urls->push([
            'loc' => route('product.show', $p->slug), 'lastmod' => $p->updated_at?->toAtomString(), 'priority' => '0.8',
        ]));

        foreach ($blog->articles() as $article) {
            $urls->push(['loc' => route('blog.show', $article['slug']), 'lastmod' => $article['date'], 'priority' => '0.5']);
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
             . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $u) {
            $xml .= '  <url><loc>' . e($u['loc']) . '</loc>'
                  . (! empty($u['lastmod']) ? '<lastmod>' . e($u['lastmod']) . '</lastmod>' : '')
                  . (! empty($u['changefreq']) ? '<changefreq>' . $u['changefreq'] . '</changefreq>' : '')
                  . '<priority>' . $u['priority'] . '</priority></url>' . "\n";
        }

        $xml .= '</urlset>' . "\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
