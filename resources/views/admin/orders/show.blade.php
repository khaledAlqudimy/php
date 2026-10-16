{{-- ════ admin/orders/show.blade.php ════ --}}
@extends('layouts.admin')
@section('title','تفاصيل الطلب')
@section('page-title','طلب: ' . $order->order_number)

@section('content')
<div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start;">

    <div>
        {{-- العناصر --}}
        <div style="background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;margin-bottom:1.5rem;">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--border);font-weight:700;font-size:.875rem;color:var(--brown);">
                <i class="fa-solid fa-bag-shopping" style="color:var(--gold);margin-left:.4rem;"></i>
                عناصر الطلب ({{ $order->items->count() }})
            </div>
            <table style="width:100%;border-collapse:collapse;font-size:.85rem;">
                <thead><tr style="background:#FAFAF9;border-bottom:1px solid var(--border);">
                    <th style="padding:.7rem 1rem;text-align:right;font-size:.72rem;color:var(--muted);">المنتج</th>
                    <th style="padding:.7rem 1rem;text-align:center;font-size:.72rem;color:var(--muted);">الكمية</th>
                    <th style="padding:.7rem 1rem;text-align:center;font-size:.72rem;color:var(--muted);">السعر</th>
                    <th style="padding:.7rem 1rem;text-align:center;font-size:.72rem;color:var(--muted);">الإجمالي</th>
                </tr></thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr style="border-bottom:1px solid var(--border);">
                            <td style="padding:.875rem 1rem;">
                                <div style="display:flex;align-items:center;gap:.75rem;">
                                    <div style="width:44px;height:44px;border-radius:var(--radius);overflow:hidden;border:1px solid var(--border);background:#F5F0EB;flex-shrink:0;">
                                        @if($item->product?->mainImage())
                                            <img src="{{ Storage::url($item->product->mainImage()) }}" style="width:100%;height:100%;object-fit:cover;">
                                        @endif
                                    </div>
                                    <span style="font-weight:600;color:var(--brown);">{{ $item->title }}</span>
                                </div>
                            </td>
                            <td style="text-align:center;padding:.875rem 1rem;">{{ $item->quantity }}</td>
                            <td style="text-align:center;padding:.875rem 1rem;">{{ number_format($item->price,0) }} ر.س</td>
                            <td style="text-align:center;padding:.875rem 1rem;font-weight:700;color:var(--brown);">{{ number_format($item->price * $item->quantity,0) }} ر.س</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="padding:1rem 1.25rem;border-top:1px solid var(--border);display:flex;flex-direction:column;gap:.4rem;align-items:flex-end;">
                @foreach([['المجموع الفرعي',$order->subtotal],['الشحن',$order->shipping_fee],['الضريبة',$order->tax_amount],['الخصم','-'.$order->discount]] as [$l,$v])
                    <div style="display:flex;gap:2rem;font-size:.85rem;color:var(--muted);">
                        <span>{{ $l }}</span>
                        <span style="font-weight:600;color:var(--text);">{{ number_format($v,0) }} ر.س</span>
                    </div>
                @endforeach
                <div style="display:flex;gap:2rem;font-size:1rem;font-weight:700;color:var(--brown);border-top:1.5px solid var(--border);padding-top:.5rem;margin-top:.25rem;">
                    <span>الإجمالي</span>
                    <span style="color:var(--gold);">{{ number_format($order->total,0) }} ر.س</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- تحديث الحالة --}}
        <div style="background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--border);font-weight:700;font-size:.875rem;color:var(--brown);">تحديث الحالة</div>
            <div style="padding:1.25rem;">
                <div style="margin-bottom:1rem;">
                    <span style="display:inline-flex;align-items:center;padding:.3rem .9rem;border-radius:100px;font-size:.8rem;font-weight:700;background:#FEF9C3;color:#854D0E;">
                        الحالة الحالية: {{ $order->statusLabel() }}
                    </span>
                </div>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf @method('PATCH')
                    <select name="status" style="width:100%;padding:.7rem .9rem;border:1.5px solid var(--border);border-radius:var(--radius);font-family:var(--font);font-size:.875rem;margin-bottom:.75rem;background:var(--bg);">
                        @foreach(['PENDING'=>'قيد الانتظار','PROCESSING'=>'قيد المعالجة','SHIPPED'=>'تم الشحن','DELIVERED'=>'تم التوصيل','CANCELLED'=>'ملغي','REFUNDED'=>'مسترجع'] as $val=>$label)
                            <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" style="width:100%;padding:.75rem;background:var(--brown);color:var(--gold);border:none;border-radius:var(--radius);font-family:var(--font);font-weight:700;cursor:pointer;font-size:.875rem;">حفظ الحالة</button>
                </form>
            </div>
        </div>

        {{-- بيانات العميل --}}
        @php $infoItems = [['fa-user','العميل',$order->user?->name],['fa-envelope','البريد',$order->user?->email],['fa-phone','الهاتف',$order->user?->phone],['fa-location-dot','العنوان',$order->address ? "{$order->address->city}، {$order->address->country}" : null],['fa-credit-card','الدفع',$order->paymentLabel() . ' — ' . $order->paymentStatusLabel()],['fa-hashtag','مرجع الدفع',$order->payment_reference],['fa-calendar-check','تاريخ الدفع',$order->paid_at?->format('d/m/Y H:i')]]; @endphp
        <div style="background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--border);font-weight:700;font-size:.875rem;color:var(--brown);">بيانات الطلب</div>
            <div style="padding:1.25rem;display:flex;flex-direction:column;gap:.75rem;">
                @foreach($infoItems as [$icon,$label,$value])
                    @if($value)
                        <div style="display:flex;gap:.75rem;align-items:flex-start;font-size:.85rem;">
                            <i class="fa-solid {{ $icon }}" style="color:var(--gold);margin-top:.2rem;flex-shrink:0;width:14px;"></i>
                            <div><span style="color:var(--muted);display:block;font-size:.72rem;">{{ $label }}</span><span style="color:var(--brown);font-weight:600;">{{ $value }}</span></div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
