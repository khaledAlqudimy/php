@extends('layouts.app')
@section('title','عناوين التوصيل')
@section('noindex', true)

@section('content')
<div style="background:var(--color-brown);padding:3rem 0 2.5rem;">
    <div class="container"><h1 style="font-size:1.75rem;font-weight:700;color:var(--color-gold);">حسابي</h1></div>
</div>

<div style="padding:2.5rem 0 5rem;">
    <div class="container">
        <nav style="display:flex;gap:.25rem;overflow-x:auto;margin-bottom:2.5rem;">
            @foreach([['account.profile','fa-circle-user','الملف الشخصي'],['account.orders','fa-receipt','طلباتي'],['account.wishlist','fa-heart','المفضلة'],['account.addresses','fa-location-dot','عناوين التوصيل']] as [$route,$icon,$label])
                <a href="{{ route($route) }}" style="display:flex;align-items:center;gap:.4rem;padding:.6rem 1.1rem;border-radius:var(--radius);font-size:.875rem;font-weight:600;white-space:nowrap;transition:all .2s;text-decoration:none;{{ request()->routeIs($route)?'background:var(--color-brown);color:var(--color-gold);':'color:var(--color-text-muted);' }}">
                    <i class="fa-{{ request()->routeIs($route)?'solid':'regular' }} {{ $icon }} fa-sm"></i> {{ $label }}
                </a>
            @endforeach
        </nav>

        <div class="stack-md" style="display:grid;grid-template-columns:1fr 340px;gap:2rem;align-items:start;">

            {{-- العناوين المحفوظة --}}
            <div>
                <div style="margin-bottom:1.25rem;display:flex;align-items:center;justify-content:space-between;">
                    <h2 style="font-size:1.1rem;font-weight:700;color:var(--color-brown);">عناوين التوصيل ({{ $addresses->count() }})</h2>
                </div>

                @if($addresses->isEmpty())
                    <div style="text-align:center;padding:4rem;background:var(--color-white);border-radius:var(--radius-lg);border:1px solid var(--color-border);color:var(--color-text-muted);">
                        <i class="fa-solid fa-map-marker-alt fa-3x" style="opacity:.15;margin-bottom:1rem;"></i>
                        <h3 style="color:var(--color-brown);margin-bottom:.5rem;">لا توجد عناوين</h3>
                        <p style="font-size:.875rem;">أضف عنواناً لتسهيل عملية الطلب</p>
                    </div>
                @else
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem;">
                        @foreach($addresses as $address)
                            <div style="background:var(--color-white);border-radius:var(--radius-lg);border:2px solid {{ $address->is_default?'var(--color-gold)':'var(--color-border)' }};padding:1.25rem;position:relative;">
                                @if($address->is_default)
                                    <span style="position:absolute;top:.75rem;left:.75rem;background:var(--color-gold);color:var(--color-brown);font-size:.65rem;font-weight:700;padding:.15rem .55rem;border-radius:100px;">افتراضي</span>
                                @endif
                                <div style="font-weight:700;color:var(--color-brown);margin-bottom:.35rem;">{{ $address->full_name }}</div>
                                <div style="font-size:.8rem;color:var(--color-text-muted);line-height:1.8;">
                                    {{ $address->phone }}<br>
                                    {{ $address->city }}@if($address->district)، {{ $address->district }}@endif<br>
                                    {{ $address->country }}
                                    @if($address->street)<br>{{ $address->street }}@endif
                                </div>
                                @if(!$address->is_default)
                                    <form action="{{ route('account.addresses.destroy', $address->id) }}" method="POST" style="margin-top:1rem;"
                                          onsubmit="return confirm('حذف هذا العنوان؟')">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="font-size:.78rem;color:#DC2626;background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:.3rem;font-family:var(--font-main);">
                                            <i class="fa-solid fa-trash-can fa-xs"></i> حذف
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- إضافة عنوان جديد --}}
            <div style="background:var(--color-white);border-radius:var(--radius-lg);border:1px solid var(--color-border);overflow:hidden;position:sticky;top:88px;">
                <div style="padding:1.1rem 1.5rem;background:var(--color-bg);border-bottom:1px solid var(--color-border);font-weight:700;color:var(--color-brown);">
                    <i class="fa-solid fa-plus" style="color:var(--color-gold);margin-left:.4rem;"></i> عنوان جديد
                </div>
                <form action="{{ route('account.addresses.store') }}" method="POST" style="padding:1.25rem;">
                    @csrf
                    @foreach([['full_name','الاسم الكامل','text','محمد عبدالله',true],['phone','رقم الهاتف','tel','+966...',true],['country','الدولة','text','المملكة العربية السعودية',true],['city','المدينة','text','الرياض',true],['district','الحي','text','',false],['street','الشارع','text','',false],['postal','الرمز البريدي','text','',false]] as [$n,$l,$t,$ph,$r])
                        <div style="margin-bottom:.875rem;">
                            <label style="display:block;font-size:.75rem;font-weight:700;color:var(--color-brown);margin-bottom:.3rem;">{{ $l }}{{ $r?' *':'' }}</label>
                            <input type="{{ $t }}" name="{{ $n }}" {{ $r?'required':'' }} placeholder="{{ $ph }}" value="{{ old($n, $t==='text'&&$n==='country'?'المملكة العربية السعودية':'' ) }}"
                                style="width:100%;padding:.6rem .9rem;border:1.5px solid var(--color-border);border-radius:var(--radius);font-family:var(--font-main);font-size:.85rem;background:var(--color-bg);direction:rtl;"
                                onfocus="this.style.borderColor='var(--color-gold)'" onblur="this.style.borderColor='var(--color-border)'">
                            @error($n)<div style="color:#DC2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>@enderror
                        </div>
                    @endforeach
                    <label style="display:flex;align-items:center;gap:.5rem;font-size:.8rem;color:var(--color-text-muted);cursor:pointer;margin-bottom:1rem;">
                        <input type="checkbox" name="is_default" value="1" style="accent-color:var(--color-gold);">
                        تعيين كعنوان افتراضي
                    </label>
                    <button type="submit" style="width:100%;padding:.8rem;background:var(--color-brown);color:var(--color-gold);border:none;border-radius:var(--radius);font-family:var(--font-main);font-weight:700;cursor:pointer;">
                        <i class="fa-solid fa-plus fa-sm"></i> إضافة العنوان
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
