{{-- ════ account/profile.blade.php ════ --}}
@extends('layouts.app')
@section('title','الملف الشخصي')
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

        <div class="stack-md" style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;align-items:start;">

            {{-- تعديل البيانات --}}
            <div style="background:var(--color-white);border-radius:var(--radius-lg);border:1px solid var(--color-border);overflow:hidden;">
                <div style="padding:1.1rem 1.5rem;background:var(--color-bg);border-bottom:1px solid var(--color-border);font-weight:700;color:var(--color-brown);display:flex;align-items:center;gap:.5rem;">
                    <i class="fa-solid fa-pen-to-square" style="color:var(--color-gold);"></i> تعديل البيانات
                </div>
                <form action="{{ route('account.profile.update') }}" method="POST" style="padding:1.5rem;">
                    @csrf @method('PUT')
                    @foreach([['name','الاسم الكامل','text',$user->name,true],['phone','رقم الجوال','tel',$user->phone,false]] as [$n,$l,$t,$v,$r])
                        <div style="margin-bottom:1.1rem;">
                            <label style="display:block;font-size:.8rem;font-weight:700;color:var(--color-brown);margin-bottom:.35rem;">{{ $l }}</label>
                            <input type="{{ $t }}" name="{{ $n }}" {{ $r?'required':'' }} value="{{ old($n,$v) }}"
                                style="width:100%;padding:.7rem 1rem;border:1.5px solid var(--color-border);border-radius:var(--radius);font-family:var(--font-main);font-size:.875rem;background:var(--color-bg);direction:rtl;"
                                onfocus="this.style.borderColor='var(--color-gold)'" onblur="this.style.borderColor='var(--color-border)'">
                            @error($n)<div style="color:#DC2626;font-size:.75rem;margin-top:.25rem;">{{ $message }}</div>@enderror
                        </div>
                    @endforeach
                    <div style="background:var(--color-bg-2);border-radius:var(--radius);padding:1rem;margin-bottom:1.1rem;">
                        <div style="font-size:.8rem;font-weight:700;color:var(--color-brown);margin-bottom:.75rem;">تغيير كلمة المرور (اختياري)</div>
                        @foreach([['password','كلمة المرور الجديدة'],['password_confirmation','تأكيد كلمة المرور']] as [$n,$l])
                            <div style="margin-bottom:.75rem;">
                                <label style="display:block;font-size:.75rem;font-weight:600;color:var(--color-text-muted);margin-bottom:.3rem;">{{ $l }}</label>
                                <input type="password" name="{{ $n }}" style="width:100%;padding:.65rem .9rem;border:1.5px solid var(--color-border);border-radius:var(--radius);font-family:var(--font-main);font-size:.875rem;background:var(--color-white);"
                                    onfocus="this.style.borderColor='var(--color-gold)'" onblur="this.style.borderColor='var(--color-border)'">
                                @error($n)<div style="color:#DC2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>@enderror
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" style="width:100%;padding:.875rem;background:var(--color-brown);color:var(--color-gold);border:none;border-radius:var(--radius);font-family:var(--font-main);font-weight:700;cursor:pointer;font-size:.925rem;">
                        <i class="fa-solid fa-floppy-disk fa-sm"></i> حفظ التغييرات
                    </button>
                </form>
            </div>

            {{-- معلومات الحساب --}}
            <div style="background:var(--color-white);border-radius:var(--radius-lg);border:1px solid var(--color-border);overflow:hidden;">
                <div style="padding:1.1rem 1.5rem;background:var(--color-bg);border-bottom:1px solid var(--color-border);font-weight:700;color:var(--color-brown);">
                    <i class="fa-solid fa-circle-info" style="color:var(--color-gold);margin-left:.4rem;"></i> معلومات الحساب
                </div>
                <div style="padding:1.5rem;">
                    <div style="width:72px;height:72px;background:var(--color-gold);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--color-brown);font-size:1.75rem;font-weight:700;margin:0 auto 1.25rem;">
                        {{ mb_substr($user->name, 0, 1) }}
                    </div>
                    @foreach([['fa-user','الاسم',$user->name],['fa-envelope','البريد الإلكتروني',$user->email],['fa-phone','الجوال',$user->phone??'لم يُضف بعد'],['fa-calendar','عضو منذ',$user->created_at->format('d/m/Y')],['fa-shield-halved','نوع الحساب',$user->role==='CUSTOMER'?'عميل':'مدير/موظف']] as [$icon,$label,$value])
                        <div style="display:flex;gap:.75rem;padding:.75rem 0;border-bottom:1px solid var(--color-border);">
                            <i class="fa-solid {{ $icon }}" style="color:var(--color-gold);margin-top:.15rem;width:16px;flex-shrink:0;"></i>
                            <div>
                                <div style="font-size:.72rem;color:var(--color-text-muted);">{{ $label }}</div>
                                <div style="font-weight:600;color:var(--color-brown);font-size:.875rem;">{{ $value }}</div>
                            </div>
                        </div>
                    @endforeach
                    <div style="margin-top:1.25rem;">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" style="width:100%;padding:.75rem;background:#FEE2E2;color:#DC2626;border:1px solid #FECACA;border-radius:var(--radius);font-family:var(--font-main);font-weight:600;cursor:pointer;font-size:.875rem;">
                                <i class="fa-solid fa-right-from-bracket fa-sm"></i> تسجيل الخروج
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
