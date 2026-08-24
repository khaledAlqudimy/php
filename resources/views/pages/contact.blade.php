@extends('layouts.app')
@section('title','تواصل معنا')

@section('content')
<div style="background:var(--color-brown);padding:4rem 0 3.5rem;text-align:center;">
    <div class="container">
        <h1 style="font-size:2.25rem;font-weight:700;color:var(--color-gold);margin-bottom:.75rem;">تواصل معنا</h1>
        <p style="color:rgba(255,255,255,.65);">نسعد بالإجابة على استفساراتك</p>
    </div>
</div>

<div style="padding:5rem 0;">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:start;">

            {{-- النموذج --}}
            <div style="background:var(--color-white);border-radius:var(--radius-lg);padding:2rem;border:1px solid var(--color-border);">
                <h2 style="font-size:1.2rem;font-weight:700;color:var(--color-brown);margin-bottom:1.5rem;">أرسل رسالة</h2>
                @if(session('success'))
                    <div style="background:#DCFCE7;color:#166534;border-radius:var(--radius);padding:.875rem 1rem;margin-bottom:1.25rem;font-weight:600;font-size:.875rem;">
                        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('contact') }}" method="POST">
                    @csrf
                    @foreach([['name','الاسم الكامل','text','محمد عبدالله',true],['email','البريد الإلكتروني','email','example@email.com',true],['phone','رقم الهاتف','tel','+966...',false],['subject','الموضوع','text','استفسار عن منتج',true]] as [$n,$l,$t,$ph,$r])
                        <div style="margin-bottom:1rem;">
                            <label style="display:block;font-size:.8rem;font-weight:700;color:var(--color-brown);margin-bottom:.35rem;">{{ $l }}{{ $r?' *':'' }}</label>
                            <input type="{{ $t }}" name="{{ $n }}" {{ $r?'required':'' }}
                                placeholder="{{ $ph }}" value="{{ old($n) }}"
                                style="width:100%;padding:.7rem 1rem;border:1.5px solid var(--color-border);border-radius:var(--radius);font-family:var(--font-main);font-size:.875rem;background:var(--color-bg);direction:rtl;transition:all .2s;"
                                onfocus="this.style.borderColor='var(--color-gold)'" onblur="this.style.borderColor='var(--color-border)'">
                        </div>
                    @endforeach
                    <div style="margin-bottom:1.5rem;">
                        <label style="display:block;font-size:.8rem;font-weight:700;color:var(--color-brown);margin-bottom:.35rem;">الرسالة *</label>
                        <textarea name="message" required rows="5"
                            placeholder="اكتب رسالتك هنا..."
                            style="width:100%;padding:.7rem 1rem;border:1.5px solid var(--color-border);border-radius:var(--radius);font-family:var(--font-main);font-size:.875rem;background:var(--color-bg);direction:rtl;resize:vertical;transition:all .2s;"
                            onfocus="this.style.borderColor='var(--color-gold)'" onblur="this.style.borderColor='var(--color-border)'">{{ old('message') }}</textarea>
                    </div>
                    <button type="submit" style="width:100%;padding:.9rem;background:var(--color-brown);color:var(--color-gold);border:none;border-radius:var(--radius);font-family:var(--font-main);font-size:.95rem;font-weight:700;cursor:pointer;transition:all .2s;"
                        onmouseover="this.style.background='var(--color-gold)';this.style.color='var(--color-brown)'"
                        onmouseout="this.style.background='var(--color-brown)';this.style.color='var(--color-gold)'">
                        <i class="fa-solid fa-paper-plane fa-sm"></i> إرسال الرسالة
                    </button>
                </form>
            </div>

            {{-- معلومات التواصل --}}
            <div>
                <h2 style="font-size:1.2rem;font-weight:700;color:var(--color-brown);margin-bottom:1.5rem;">معلومات التواصل</h2>
                @php
                    $info = [
                        ['fa-envelope','البريد الإلكتروني',\App\Models\Setting::get('site_email','info@qet3a-w-qesa.sa'),'mailto:'],
                        ['fa-phone','الهاتف',\App\Models\Setting::get('site_phone','+966 XX XXX XXXX'),'tel:'],
                        ['fa-location-dot','العنوان',\App\Models\Setting::get('site_address','المملكة العربية السعودية'),null],
                    ];
                @endphp
                @foreach($info as [$icon,$label,$value,$prefix])
                    <div style="display:flex;gap:1rem;align-items:flex-start;background:var(--color-white);border-radius:var(--radius-lg);padding:1.25rem;border:1px solid var(--color-border);margin-bottom:1rem;">
                        <div style="width:44px;height:44px;border-radius:var(--radius);background:rgba(182,138,74,.1);border:1px solid rgba(182,138,74,.2);display:flex;align-items:center;justify-content:center;color:var(--color-gold);flex-shrink:0;">
                            <i class="fa-solid {{ $icon }}"></i>
                        </div>
                        <div>
                            <div style="font-size:.75rem;color:var(--color-text-muted);margin-bottom:.2rem;">{{ $label }}</div>
                            @if($prefix)
                                <a href="{{ $prefix }}{{ $value }}" style="font-weight:600;color:var(--color-brown);">{{ $value }}</a>
                            @else
                                <div style="font-weight:600;color:var(--color-brown);">{{ $value }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach

                @php $whatsapp = \App\Models\Setting::get('site_whatsapp'); @endphp
                @if($whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/\D/','',$whatsapp) }}" target="_blank"
                       style="display:flex;align-items:center;justify-content:center;gap:.6rem;padding:1rem;background:#25D366;color:#fff;border-radius:var(--radius-lg);font-weight:700;font-size:.95rem;text-decoration:none;margin-top:1rem;">
                        <i class="fa-brands fa-whatsapp fa-lg"></i> تواصل عبر واتساب
                    </a>
                @endif

                <div style="background:var(--color-bg-2);border-radius:var(--radius-lg);padding:1.25rem;border:1px solid var(--color-border);margin-top:1rem;">
                    <div style="font-weight:700;color:var(--color-brown);font-size:.875rem;margin-bottom:.75rem;">ساعات العمل</div>
                    <div style="font-size:.85rem;color:var(--color-text-muted);line-height:2;">
                        الأحد – الخميس: 9:00 ص – 9:00 م<br>
                        الجمعة: 2:00 م – 9:00 م<br>
                        السبت: 10:00 ص – 8:00 م
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
