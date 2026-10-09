{{--
    قالب الصفحات القانونية (الخصوصية / الشروط)
    المتغيرات: $heading, $subheading, $updated, $intro, $sections = [[عنوان, [فقرات...]], ...]
--}}
<div style="background:var(--color-brown);padding:4rem 0 3.5rem;text-align:center;">
    <div class="container">
        <h1 style="font-size:2.25rem;font-weight:700;color:var(--color-gold);margin-bottom:.75rem;">{{ $heading }}</h1>
        <p style="color:rgba(255,255,255,.65);">{{ $subheading }}</p>
    </div>
</div>

<div style="padding:4rem 0;">
    <div class="container" style="max-width:860px;">
        <div style="font-size:.8rem;color:var(--color-text-muted);margin-bottom:1.25rem;">
            <i class="fa-regular fa-calendar"></i> آخر تحديث: {{ $updated }}
        </div>

        <p style="font-size:.95rem;color:var(--color-text);line-height:2;margin-bottom:2rem;">{{ $intro }}</p>

        @foreach($sections as $i => [$title, $paragraphs])
            <section style="background:var(--color-white);border-radius:var(--radius-lg);padding:1.75rem;border:1px solid var(--color-border);margin-bottom:1rem;">
                <h2 style="font-size:1.1rem;font-weight:700;color:var(--color-brown);margin-bottom:.85rem;display:flex;align-items:center;gap:.6rem;">
                    <span style="width:28px;height:28px;background:var(--color-gold);color:var(--color-brown);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:.8rem;flex-shrink:0;">{{ $i + 1 }}</span>
                    {{ $title }}
                </h2>
                @foreach($paragraphs as $p)
                    <p style="font-size:.9rem;color:var(--color-text-muted);line-height:1.95;{{ !$loop->last ? 'margin-bottom:.6rem;' : '' }}">{{ $p }}</p>
                @endforeach
            </section>
        @endforeach

        <div style="background:rgba(182,138,74,.06);border:1px solid rgba(182,138,74,.2);border-radius:var(--radius-lg);padding:1.5rem;margin-top:1.5rem;font-size:.9rem;color:var(--color-text-muted);line-height:1.9;">
            <i class="fa-solid fa-envelope" style="color:var(--color-gold);margin-left:.4rem;"></i>
            لأي استفسار حول هذه السياسة تواصل معنا عبر
            <a href="{{ route('contact') }}" style="color:var(--color-gold);font-weight:600;">صفحة التواصل</a>
            @if($email = \App\Models\Setting::contact('site_email'))
                أو على البريد <a href="mailto:{{ $email }}" style="color:var(--color-gold);font-weight:600;">{{ $email }}</a>
            @endif
        </div>
    </div>
</div>
