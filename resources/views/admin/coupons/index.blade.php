{{-- ════════════════════════════════════════
   admin/coupons/index.blade.php
════════════════════════════════════════ --}}
@extends('layouts.admin')
@section('title','الكوبونات')
@section('page-title','إدارة الكوبونات')

@section('content')
<div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start;">

    {{-- جدول الكوبونات --}}
    <div style="background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;font-size:.85rem;">
            <thead><tr style="background:#FAFAF9;border-bottom:1px solid var(--border);">
                @foreach(['الكود','النوع','القيمة','الحد الأدنى','الاستخدام','الانتهاء','الحالة',''] as $h)
                    <th style="padding:.75rem 1rem;text-align:right;font-size:.72rem;font-weight:700;color:var(--muted);">{{ $h }}</th>
                @endforeach
            </tr></thead>
            <tbody>
                @forelse($coupons as $coupon)
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:.875rem 1rem;font-family:monospace;font-weight:700;color:var(--gold);font-size:.875rem;">{{ $coupon->code }}</td>
                        <td style="padding:.875rem 1rem;">
                            <span style="background:{{ $coupon->type==='PERCENTAGE'?'#DBEAFE':'#DCFCE7' }};color:{{ $coupon->type==='PERCENTAGE'?'#1E40AF':'#166534' }};padding:.2rem .6rem;border-radius:100px;font-size:.72rem;font-weight:700;">
                                {{ $coupon->type === 'PERCENTAGE' ? 'نسبة %' : 'مبلغ ثابت' }}
                            </span>
                        </td>
                        <td style="padding:.875rem 1rem;font-weight:700;color:var(--brown);">{{ $coupon->type==='PERCENTAGE' ? $coupon->value.'%' : number_format($coupon->value,0).' ر.س' }}</td>
                        <td style="padding:.875rem 1rem;color:var(--muted);font-size:.825rem;">{{ $coupon->min_order ? number_format($coupon->min_order,0).' ر.س' : '—' }}</td>
                        <td style="padding:.875rem 1rem;color:var(--muted);font-size:.825rem;">{{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}</td>
                        <td style="padding:.875rem 1rem;font-size:.8rem;color:var(--muted);">{{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : '—' }}</td>
                        <td style="padding:.875rem 1rem;">
                            <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" style="padding:.25rem .7rem;border-radius:100px;border:none;cursor:pointer;font-size:.72rem;font-weight:700;background:{{ $coupon->is_active?'#DCFCE7':'#FEE2E2' }};color:{{ $coupon->is_active?'#166534':'#991B1B' }};">
                                    {{ $coupon->is_active ? 'نشط' : 'معطّل' }}
                                </button>
                            </form>
                        </td>
                        <td style="padding:.875rem 1rem;">
                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('حذف الكوبون؟')">
                                @csrf @method('DELETE')
                                <button type="submit" style="padding:.35rem .6rem;background:#FEE2E2;color:#DC2626;border:1px solid #FECACA;border-radius:var(--radius);cursor:pointer;font-size:.75rem;"><i class="fa-solid fa-trash-can fa-xs"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" style="text-align:center;padding:3rem;color:var(--muted);">لا توجد كوبونات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- إضافة كوبون جديد --}}
    <div style="background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;position:sticky;top:88px;">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--border);font-weight:700;font-size:.875rem;color:var(--brown);">
            <i class="fa-solid fa-ticket" style="color:var(--gold);margin-left:.4rem;"></i> كوبون جديد
        </div>
        <form action="{{ route('admin.coupons.store') }}" method="POST" style="padding:1.25rem;">
            @csrf
            @foreach([
                ['code','الكود','text','أدخل الكود بالإنجليزية','SALE20',true],
                ['value','القيمة','number','مثال: 20 أو 50',null,true],
                ['min_order','الحد الأدنى للطلب','number','0 = بدون حد',null,false],
                ['usage_limit','عدد مرات الاستخدام','number','اتركه فارغاً للا محدود',null,false],
            ] as [$name,$label,$type,$placeholder,$default,$req])
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--brown);margin-bottom:.3rem;">{{ $label }}</label>
                    <input type="{{ $type }}" name="{{ $name }}"
                        style="width:100%;padding:.65rem .85rem;border:1.5px solid var(--border);border-radius:var(--radius);font-family:var(--font);font-size:.875rem;background:var(--bg);direction:ltr;"
                        placeholder="{{ $placeholder }}" {{ $req ? 'required' : '' }}
                        value="{{ old($name, $default) }}" min="0">
                    @error($name)<div style="color:#DC2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>@enderror
                </div>
            @endforeach

            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:.78rem;font-weight:700;color:var(--brown);margin-bottom:.3rem;">نوع الخصم</label>
                <select name="type" style="width:100%;padding:.65rem .85rem;border:1.5px solid var(--border);border-radius:var(--radius);font-family:var(--font);font-size:.875rem;background:var(--bg);" required>
                    <option value="PERCENTAGE">نسبة مئوية (%)</option>
                    <option value="FIXED">مبلغ ثابت (ر.س)</option>
                </select>
            </div>

            <div style="margin-bottom:1.25rem;">
                <label style="display:block;font-size:.78rem;font-weight:700;color:var(--brown);margin-bottom:.3rem;">تاريخ الانتهاء</label>
                <input type="date" name="expires_at" style="width:100%;padding:.65rem .85rem;border:1.5px solid var(--border);border-radius:var(--radius);font-family:var(--font);font-size:.875rem;background:var(--bg);">
            </div>

            <button type="submit" style="width:100%;padding:.8rem;background:var(--brown);color:var(--gold);border:none;border-radius:var(--radius);font-family:var(--font);font-weight:700;cursor:pointer;font-size:.9rem;">
                <i class="fa-solid fa-plus fa-xs"></i> إضافة الكوبون
            </button>
        </form>
    </div>
</div>
@endsection
