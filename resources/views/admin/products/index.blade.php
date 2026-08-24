@extends('layouts.admin')
@section('title', 'المنتجات')
@section('page-title', 'إدارة المنتجات')

@push('styles')
<style>
.toolbar { display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;margin-bottom:1.5rem; }
.search-bar { flex:1;min-width:200px;display:flex;gap:.5rem; }
.admin-input { padding:.6rem .9rem;border:1.5px solid var(--border);border-radius:var(--radius);font-family:var(--font);font-size:.875rem;color:var(--text);background:var(--white); }
.admin-input:focus { outline:none;border-color:var(--gold); }
.admin-select { padding:.6rem .9rem;border:1.5px solid var(--border);border-radius:var(--radius);font-family:var(--font);font-size:.875rem;background:var(--white);cursor:pointer; }

.btn-a { display:inline-flex;align-items:center;gap:.4rem;padding:.6rem 1rem;border-radius:var(--radius);font-family:var(--font);font-size:.8rem;font-weight:600;cursor:pointer;border:none;transition:all .2s;text-decoration:none; }
.btn-primary { background:var(--brown);color:var(--gold); }
.btn-primary:hover { background:var(--gold);color:var(--brown); }
.btn-danger { background:#FEE2E2;color:#DC2626;border:1px solid #FECACA; }
.btn-danger:hover { background:#DC2626;color:#fff; }
.btn-edit { background:#EFF6FF;color:#1D4ED8;border:1px solid #BFDBFE; }
.btn-edit:hover { background:#1D4ED8;color:#fff; }

.tbl-wrap { background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden; }
.tbl { width:100%;border-collapse:collapse;font-size:.85rem; }
.tbl th { padding:.75rem 1rem;text-align:right;font-size:.72rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;background:#FAFAF9;border-bottom:1px solid var(--border); }
.tbl td { padding:.875rem 1rem;border-bottom:1px solid var(--border);vertical-align:middle; }
.tbl tr:last-child td { border:none; }
.tbl tr:hover td { background:#FAFAF9; }

.product-thumb { width:48px;height:48px;border-radius:var(--radius);overflow:hidden;border:1px solid var(--border);background:#F5F0EB;flex-shrink:0; }
.product-thumb img { width:100%;height:100%;object-fit:cover; }

.toggle { display:inline-flex;align-items:center;gap:.35rem;padding:.2rem .65rem;border-radius:100px;font-size:.72rem;font-weight:700; }
.toggle-on  { background:#DCFCE7;color:#166534; }
.toggle-off { background:#FEE2E2;color:#991B1B; }

.badge-rare { background:#EDE9FE;color:#7C3AED;padding:.15rem .55rem;border-radius:100px;font-size:.68rem;font-weight:700; }
.badge-feat { background:rgba(182,138,74,.12);color:var(--gold);padding:.15rem .55rem;border-radius:100px;font-size:.68rem;font-weight:700; }

.pagination-bar { padding:1rem 1.25rem;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;font-size:.8rem;color:var(--muted); }
</style>
@endpush

@section('content')

<div class="toolbar">
    <form method="GET" action="{{ route('admin.products.index') }}" style="display:contents;">
        <div class="search-bar">
            <input type="text" name="q" class="admin-input" placeholder="بحث باسم المنتج أو SKU..." value="{{ request('q') }}" style="flex:1;">
            <select name="category" class="admin-select">
                <option value="">كل التصنيفات</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="status" class="admin-select">
                <option value="">كل الحالات</option>
                <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>نشط</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>غير نشط</option>
            </select>
            <button type="submit" class="btn-a btn-primary">بحث</button>
            <a href="{{ route('admin.products.index') }}" class="btn-a" style="background:var(--bg);border:1px solid var(--border);color:var(--muted);">مسح</a>
        </div>
    </form>
    <a href="{{ route('admin.products.create') }}" class="btn-a btn-primary">
        <i class="fa-solid fa-plus fa-xs"></i> منتج جديد
    </a>
</div>

<div class="tbl-wrap">
    <table class="tbl">
        <thead>
            <tr>
                <th>المنتج</th>
                <th>SKU</th>
                <th>التصنيف</th>
                <th>السعر</th>
                <th>المخزون</th>
                <th>الحالة</th>
                <th>المشاهدات</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.75rem;">
                            <div class="product-thumb">
                                @if($product->mainImage())
                                    <img src="{{ Storage::url($product->mainImage()) }}" alt="{{ $product->title }}">
                                @endif
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:.875rem;color:var(--brown);">{{ Str::limit($product->title, 36) }}</div>
                                <div style="margin-top:.2rem;display:flex;gap:.3rem;">
                                    @if($product->is_rare)    <span class="badge-rare">نادرة</span>  @endif
                                    @if($product->is_featured) <span class="badge-feat">مميزة</span> @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td style="font-family:monospace;font-size:.78rem;color:var(--muted);">{{ $product->sku }}</td>
                    <td style="font-size:.825rem;">{{ $product->category?->name }}</td>
                    <td style="font-weight:700;color:var(--brown);">{{ number_format($product->price, 0) }} ر.س</td>
                    <td>
                        <span style="font-weight:700;color:{{ $product->stock > 0 ? 'var(--brown)' : '#DC2626' }};">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td>
                        <span class="toggle {{ $product->is_active ? 'toggle-on' : 'toggle-off' }}">
                            {{ $product->is_active ? 'نشط' : 'مخفي' }}
                        </span>
                    </td>
                    <td style="color:var(--muted);font-size:.825rem;">{{ number_format($product->views_count) }}</td>
                    <td>
                        <div style="display:flex;gap:.4rem;">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn-a btn-edit">
                                <i class="fa-solid fa-pen fa-xs"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-a btn-danger">
                                    <i class="fa-solid fa-trash-can fa-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:3rem;color:var(--muted);">
                        <i class="fa-solid fa-box-open fa-2x" style="opacity:.2;margin-bottom:.75rem;display:block;"></i>
                        لا توجد منتجات مطابقة
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-bar">
        <span>عرض {{ $products->firstItem() }}–{{ $products->lastItem() }} من {{ $products->total() }}</span>
        {{ $products->links() }}
    </div>
</div>
@endsection
