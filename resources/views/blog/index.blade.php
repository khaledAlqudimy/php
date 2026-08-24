@extends('layouts.app')
@section('title', 'المدونة')

@push('styles')
<style>
.blog-hero { background:var(--color-brown);padding:4rem 0 3.5rem;text-align:center; }
.blog-hero h1 { font-size:2.25rem;font-weight:700;color:var(--color-gold);margin-bottom:.75rem; }
.blog-hero p  { color:rgba(255,255,255,.65);max-width:480px;margin:0 auto; }

.blog-page { padding:4rem 0 5rem; }

.blog-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(320px,1fr));
    gap:1.75rem;
}

.blog-card {
    background:var(--color-white);
    border-radius:var(--radius-lg);
    border:1px solid var(--color-border);
    overflow:hidden;
    transition:var(--transition);
    display:flex; flex-direction:column;
    text-decoration:none; color:inherit;
}
.blog-card:hover { transform:translateY(-5px);box-shadow:var(--shadow-lg);border-color:var(--color-gold); }

.blog-card-img {
    aspect-ratio:16/9;
    background:linear-gradient(135deg,var(--color-brown),#2A1A10);
    display:flex; align-items:center; justify-content:center;
    font-size:3rem; color:var(--color-gold); opacity:.6;
    position:relative;
}

.blog-cat {
    position:absolute; top:.75rem; right:.75rem;
    background:var(--color-gold); color:var(--color-brown);
    font-size:.68rem; font-weight:700; padding:.2rem .65rem;
    border-radius:100px;
}

.blog-card-body { padding:1.25rem;flex:1;display:flex;flex-direction:column;gap:.5rem; }

.blog-card-title {
    font-size:.975rem; font-weight:700; color:var(--color-brown);
    line-height:1.4; display:-webkit-box; -webkit-line-clamp:2;
    -webkit-box-orient:vertical; overflow:hidden;
}

.blog-card-excerpt {
    font-size:.83rem; color:var(--color-text-muted); line-height:1.7;
    display:-webkit-box; -webkit-line-clamp:3;
    -webkit-box-orient:vertical; overflow:hidden; flex:1;
}

.blog-card-footer {
    display:flex; align-items:center; justify-content:space-between;
    margin-top:auto; padding-top:.875rem;
    border-top:1px solid var(--color-border);
    font-size:.75rem; color:var(--color-text-muted);
}

.blog-read-more {
    display:inline-flex; align-items:center; gap:.3rem;
    color:var(--color-gold); font-weight:700; font-size:.8rem;
}
</style>
@endpush

@section('content')
<div class="blog-hero">
    <div class="container">
        <span style="display:inline-block;background:rgba(182,138,74,.15);border:1px solid rgba(182,138,74,.3);color:var(--color-gold);font-size:.8rem;font-weight:700;padding:.35rem 1rem;border-radius:100px;margin-bottom:1rem;">
            <i class="fa-solid fa-pen-nib fa-xs"></i> مقالات ومعرفة
        </span>
        <h1>مدونة قطعة وقصة</h1>
        <p>مقالات متخصصة في عالم التحف والأنتيك، تاريخ القطع، دليل الاقتناء والعناية</p>
    </div>
</div>

<div class="blog-page">
    <div class="container">
        <div class="blog-grid">
            @foreach($articles as $article)
                <a href="{{ route('blog.show', $article['slug']) }}" class="blog-card">
                    <div class="blog-card-img">
                        <i class="fa-solid {{ $article['icon'] }}"></i>
                        <span class="blog-cat">{{ $article['category'] }}</span>
                    </div>
                    <div class="blog-card-body">
                        <h2 class="blog-card-title">{{ $article['title'] }}</h2>
                        <p class="blog-card-excerpt">{{ $article['excerpt'] }}</p>
                        <div class="blog-card-footer">
                            <span>
                                <i class="fa-regular fa-clock fa-xs"></i>
                                {{ $article['read_time'] }} دقائق قراءة
                            </span>
                            <span class="blog-read-more">
                                اقرأ المزيد <i class="fa-solid fa-arrow-left fa-xs"></i>
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
