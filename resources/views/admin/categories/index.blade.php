{{-- ════ admin/categories/index.blade.php ════ --}}
@extends('layouts.admin')
@section('title','التصنيفات')
@section('page-title','إدارة التصنيفات')

@section('content')
<div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start;">

    <div style="background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;font-size:.85rem;">
            <thead><tr style="background:#FAFAF9;border-bottom:1px solid var(--border);">
                @foreach(['الاسم','الأب','عدد المنتجات','الترتيب','']) as $h)
                    <th style="padding:.75rem 1rem;text-align:right;font-size:.72rem;font-weight:700;color:var(--muted);">{{ $h }}</th>
                @endforeach
            </tr></thead>
            <tbody>
                @forelse($categories as $cat)
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:.875rem 1rem;font-weight:700;color:var(--brown);">{{ $cat->name }}</td>
                        <td style="padding:.875rem 1rem;color:var(--muted);font-size:.825rem;">{{ $cat->parent?->name ?? '—' }}</td>
                        <td style="padding:.875rem 1rem;color:var(--muted);">{{ $cat->products_count }}</td>
                        <td style="padding:.875rem 1rem;color:var(--muted);">{{ $cat->order }}</td>
                        <td style="padding:.875rem 1rem;">
                            <div style="display:flex;gap:.4rem;">
                                <button onclick="openEdit({{ $cat->id }},'{{ addslashes($cat->name) }}',{{ $cat->order }})"
                                    style="padding:.35rem .7rem;background:#EFF6FF;color:#1D4ED8;border:1px solid #BFDBFE;border-radius:var(--radius);cursor:pointer;font-size:.75rem;">
                                    <i class="fa-solid fa-pen fa-xs"></i>
                                </button>
                                @if(!$cat->products_count)
                                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('حذف التصنيف؟')">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="padding:.35rem .7rem;background:#FEE2E2;color:#DC2626;border:1px solid #FECACA;border-radius:var(--radius);cursor:pointer;font-size:.75rem;">
                                            <i class="fa-solid fa-trash-can fa-xs"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--muted);">لا توجد تصنيفات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- إضافة تصنيف --}}
    <div style="position:sticky;top:88px;">
        <div style="background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;margin-bottom:1rem;">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--border);font-weight:700;font-size:.875rem;color:var(--brown);">تصنيف جديد</div>
            <form action="{{ route('admin.categories.store') }}" method="POST" style="padding:1.25rem;">
                @csrf
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--brown);margin-bottom:.3rem;">اسم التصنيف <span style="color:#DC2626">*</span></label>
                    <input type="text" name="name" required placeholder="مثال: الأثاث الكلاسيكي"
                        style="width:100%;padding:.65rem .9rem;border:1.5px solid var(--border);border-radius:var(--radius);font-family:var(--font);font-size:.875rem;background:var(--bg);">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--brown);margin-bottom:.3rem;">التصنيف الأب</label>
                    <select name="parent_id" style="width:100%;padding:.65rem .9rem;border:1.5px solid var(--border);border-radius:var(--radius);font-family:var(--font);font-size:.875rem;background:var(--bg);">
                        <option value="">بدون أب (رئيسي)</option>
                        @foreach($categories->whereNull('parent_id') as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:1.25rem;">
                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--brown);margin-bottom:.3rem;">الترتيب</label>
                    <input type="number" name="order" value="0" min="0"
                        style="width:100%;padding:.65rem .9rem;border:1.5px solid var(--border);border-radius:var(--radius);font-family:var(--font);font-size:.875rem;background:var(--bg);">
                </div>
                <button type="submit" style="width:100%;padding:.8rem;background:var(--brown);color:var(--gold);border:none;border-radius:var(--radius);font-family:var(--font);font-weight:700;cursor:pointer;">
                    <i class="fa-solid fa-plus fa-xs"></i> إضافة
                </button>
            </form>
        </div>

        {{-- نموذج التعديل المخفي --}}
        <div id="editCard" style="background:var(--white);border-radius:var(--radius);border:2px solid var(--gold);overflow:hidden;display:none;">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--border);font-weight:700;font-size:.875rem;color:var(--brown);">تعديل التصنيف</div>
            <form id="editForm" method="POST" style="padding:1.25rem;">
                @csrf @method('PATCH')
                <input type="text" name="name" id="editName"
                    style="width:100%;padding:.65rem .9rem;border:1.5px solid var(--border);border-radius:var(--radius);font-family:var(--font);font-size:.875rem;background:var(--bg);margin-bottom:1rem;">
                <input type="number" name="order" id="editOrder" min="0"
                    style="width:100%;padding:.65rem .9rem;border:1.5px solid var(--border);border-radius:var(--radius);font-family:var(--font);font-size:.875rem;background:var(--bg);margin-bottom:1.25rem;">
                <button type="submit" style="width:100%;padding:.8rem;background:var(--gold);color:var(--brown);border:none;border-radius:var(--radius);font-family:var(--font);font-weight:700;cursor:pointer;">حفظ</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openEdit(id, name, order) {
    document.getElementById('editCard').style.display = 'block';
    document.getElementById('editForm').action = `/admin/categories/${id}`;
    document.getElementById('editName').value  = name;
    document.getElementById('editOrder').value = order;
}
</script>
@endpush
@endsection
