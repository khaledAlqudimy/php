@extends('layouts.app')
@section('title', $article['title'])

@push('styles')
<style>
.article-hero { background:var(--color-brown);padding:4rem 0 3.5rem; }
.article-hero-inner { max-width:760px;margin:0 auto;text-align:center; }
.article-cat { display:inline-block;background:var(--color-gold);color:var(--color-brown);font-size:.75rem;font-weight:700;padding:.25rem .85rem;border-radius:100px;margin-bottom:1rem; }
.article-title { font-size:clamp(1.5rem,3.5vw,2.25rem);font-weight:700;color:#fff;line-height:1.3;margin-bottom:1rem; }
.article-meta { display:flex;align-items:center;justify-content:center;gap:1.5rem;color:rgba(255,255,255,.55);font-size:.82rem; }
.article-meta span { display:flex;align-items:center;gap:.35rem; }

.article-page { padding:4rem 0 5rem; }
.article-layout { display:grid;grid-template-columns:1fr 280px;gap:3rem;align-items:start;max-width:1100px;margin:0 auto; }

/* Content */
.article-body {
    background:var(--color-white);
    border-radius:var(--radius-lg);
    border:1px solid var(--color-border);
    overflow:hidden;
}
.article-banner {
    aspect-ratio:16/7;
    background:linear-gradient(135deg,var(--color-brown),#2A1A10);
    display:flex;align-items:center;justify-content:center;
    font-size:5rem;color:var(--color-gold);opacity:.5;
}
.article-content {
    padding:2rem;
    font-size:.95rem;line-height:2;
    color:var(--color-text);
}
.article-content p { margin-bottom:1.25rem; }
.article-content h2 { font-size:1.2rem;font-weight:700;color:var(--color-brown);margin:2rem 0 .875rem; }
.article-content blockquote {
    border-right:4px solid var(--color-gold);
    padding:.875rem 1.25rem;
    background:var(--color-bg);
    border-radius:0 var(--radius) var(--radius) 0;
    margin:1.5rem 0;
    font-style:italic;color:var(--color-text-muted);
}

/* Share */
.article-share { display:flex;align-items:center;gap:.75rem;padding:1.25rem 2rem;border-top:1px solid var(--color-border);font-size:.82rem;color:var(--color-text-muted); }
.share-btn { width:34px;height:34px;border-radius:50%;background:var(--color-bg);border:1px solid var(--color-border);display:flex;align-items:center;justify-content:center;color:var(--color-text-muted);font-size:.85rem;transition:var(--transition); }
.share-btn:hover { border-color:var(--color-gold);color:var(--color-gold); }

/* Sidebar */
.article-sidebar { position:sticky;top:88px;display:flex;flex-direction:column;gap:1.25rem; }

.sidebar-card { background:var(--color-white);border-radius:var(--radius-lg);border:1px solid var(--color-border);overflow:hidden; }
.sidebar-head { padding:.875rem 1.1rem;border-bottom:1px solid var(--color-border);font-weight:700;font-size:.85rem;color:var(--color-brown); }

.related-item { display:flex;gap:.75rem;align-items:flex-start;padding:.75rem 1.1rem;border-bottom:1px solid var(--color-border);text-decoration:none;color:inherit;transition:background .2s; }
.related-item:last-child { border:none; }
.related-item:hover { background:var(--color-bg); }
.related-icon { width:36px;height:36px;border-radius:var(--radius);background:var(--color-bg-2);display:flex;align-items:center;justify-content:center;color:var(--color-gold);font-size:.875rem;flex-shrink:0; }
.related-title { font-size:.8rem;font-weight:600;color:var(--color-brown);line-height:1.4; }
.related-time  { font-size:.72rem;color:var(--color-text-muted);margin-top:.2rem; }

@media (max-width:900px) {
    .article-layout { grid-template-columns:1fr; }
    .article-sidebar { position:static; }
}
</style>
@endpush

@section('content')

<div class="article-hero">
    <div class="container article-hero-inner">
        <span class="article-cat">{{ $article['category'] }}</span>
        <h1 class="article-title">{{ $article['title'] }}</h1>
        <div class="article-meta">
            <span><i class="fa-regular fa-clock fa-xs"></i> {{ $article['read_time'] }} دقائق قراءة</span>
            <span><i class="fa-regular fa-calendar fa-xs"></i> {{ \Carbon\Carbon::parse($article['date'])->translatedFormat('d F Y') }}</span>
        </div>
    </div>
</div>

<div class="article-page">
    <div class="container">
        <div class="article-layout">

            {{-- ── المقالة ── --}}
            <div>
                {{-- Breadcrumb --}}
                <nav style="display:flex;align-items:center;gap:.5rem;font-size:.8rem;color:var(--color-text-muted);margin-bottom:1.5rem;">
                    <a href="{{ route('home') }}" style="color:var(--color-text-muted);">الرئيسية</a>
                    <span>/</span>
                    <a href="{{ route('blog.index') }}" style="color:var(--color-text-muted);">المدونة</a>
                    <span>/</span>
                    <span style="color:var(--color-brown);font-weight:600;">{{ Str::limit($article['title'], 40) }}</span>
                </nav>

                <div class="article-body">
                    <div class="article-banner">
                        <i class="fa-solid {{ $article['icon'] }}"></i>
                    </div>
                    <div class="article-content">
                        <p style="font-size:1.05rem;color:var(--color-text-muted);font-style:italic;border-bottom:1px solid var(--color-border);padding-bottom:1.25rem;margin-bottom:1.5rem;">
                            {{ $article['excerpt'] }}
                        </p>

                        <p>{{ $article['content'] }}</p>

                        <h2>ما الذي يجعل هذا الموضوع مهماً؟</h2>
                        <p>في عالم التحف والأنتيك، المعرفة هي سلاحك الأقوى. سواء كنت جامعاً متمرساً أو مبتدئاً في رحلة الاقتناء، فهم هذا الجانب يحميك من الأخطاء ويمنحك ثقة أكبر في قراراتك.</p>

                        <blockquote>
                            "القطعة الأثرية لا تُقاس بسعرها، بل بالقصة التي تحملها والتاريخ الذي تمثله."
                        </blockquote>

                        <h2>نصائح عملية للمبتدئين</h2>
                        <p>ابدأ بتعلم الأساسيات قبل إنفاق أموالك. اقرأ الكتب المتخصصة، تابع دور المزادات العالمية، وتواصل مع خبراء موثوقين. في قطعة وقصة، فريقنا دائماً جاهز لإرشادك.</p>

                        <p>الصبر فضيلة في عالم المقتنيات. القطعة المثالية تستحق الانتظار، وعجلة الشراء قد تقودك لندم طويل. خذ وقتك في الدراسة والمقارنة قبل أي قرار.</p>
                    </div>

                    {{-- المشاركة --}}
                    <div class="article-share">
                        <span>شارك المقال:</span>
                        <a href="https://wa.me/?text={{ urlencode($article['title'] . ' - ' . request()->url()) }}" target="_blank" class="share-btn">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($article['title']) }}&url={{ urlencode(request()->url()) }}" target="_blank" class="share-btn">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                        <button class="share-btn" onclick="navigator.clipboard.writeText(window.location.href)">
                            <i class="fa-solid fa-link"></i>
                        </button>
                    </div>
                </div>

                {{-- CTA --}}
                <div style="background:var(--color-brown);border-radius:var(--radius-lg);padding:2rem;margin-top:1.5rem;text-align:center;">
                    <div style="color:var(--color-gold);font-size:.8rem;font-weight:700;letter-spacing:1px;margin-bottom:.75rem;">هل أثار اهتمامك موضوع المقال؟</div>
                    <h3 style="color:#fff;font-size:1.1rem;font-weight:700;margin-bottom:.75rem;">تصفح مجموعتنا من القطع المختارة</h3>
                    <a href="{{ route('shop') }}" style="display:inline-flex;align-items:center;gap:.5rem;padding:.75rem 1.75rem;background:var(--color-gold);color:var(--color-brown);border-radius:var(--radius);font-weight:700;font-size:.9rem;text-decoration:none;">
                        <i class="fa-solid fa-bag-shopping fa-sm"></i> تصفح المتجر
                    </a>
                </div>
            </div>

            {{-- ── Sidebar ── --}}
            <div class="article-sidebar">
                <div class="sidebar-card">
                    <div class="sidebar-head">
                        <i class="fa-solid fa-newspaper" style="color:var(--color-gold);margin-left:.4rem;"></i>
                        مقالات ذات صلة
                    </div>
                    @foreach($related as $rel)
                        <a href="{{ route('blog.show', $rel['slug']) }}" class="related-item">
                            <div class="related-icon">
                                <i class="fa-solid {{ $rel['icon'] }} fa-sm"></i>
                            </div>
                            <div>
                                <div class="related-title">{{ $rel['title'] }}</div>
                                <div class="related-time">{{ $rel['read_time'] }} د · {{ $rel['category'] }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- باحث شخصي CTA --}}
                <div class="sidebar-card" style="background:linear-gradient(135deg,#0F1E2E,#1A2A3A);border-color:#1A2A3A;">
                    <div style="padding:1.25rem;">
                        <div style="color:var(--color-gold);font-size:.75rem;font-weight:700;margin-bottom:.5rem;">خدمة مميزة</div>
                        <div style="color:#fff;font-weight:700;margin-bottom:.5rem;font-size:.9rem;">هل تبحث عن قطعة بعينها؟</div>
                        <div style="color:rgba(255,255,255,.6);font-size:.8rem;line-height:1.6;margin-bottom:1rem;">خبراؤنا يبحثون عنها لك في أرجاء العالم</div>
                        <a href="{{ route('personal-shopper.index') }}" style="display:flex;align-items:center;justify-content:center;gap:.4rem;padding:.6rem 1rem;background:var(--color-gold);color:var(--color-brown);border-radius:var(--radius);font-weight:700;font-size:.82rem;text-decoration:none;">
                            <i class="fa-solid fa-magnifying-glass fa-xs"></i> الباحث الشخصي
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
