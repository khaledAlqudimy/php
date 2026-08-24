@extends('layouts.admin')
@section('title', isset($product) ? 'تعديل المنتج' : 'إضافة منتج')
@section('page-title', isset($product) ? 'تعديل: ' . $product->title : 'إضافة منتج جديد')

@push('styles')
<style>
.product-form-grid {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 1.5rem;
    align-items: start;
}

.form-card { background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;margin-bottom:1.5rem; }
.form-card-head { padding:1rem 1.25rem;border-bottom:1px solid var(--border);font-weight:700;font-size:.875rem;color:var(--brown);display:flex;align-items:center;gap:.5rem; }
.form-card-head i { color:var(--gold); }
.form-card-body { padding:1.25rem; }

.form-group { margin-bottom:1.1rem; }
.form-label { display:block;font-size:.8rem;font-weight:700;color:var(--brown);margin-bottom:.35rem; }
.form-input, .form-select, .form-textarea {
    width:100%;padding:.7rem .9rem;
    border:1.5px solid var(--border);border-radius:var(--radius);
    font-family:var(--font);font-size:.875rem;color:var(--text);
    background:var(--bg);direction:rtl;transition:all .2s;
}
.form-input:focus,.form-select:focus,.form-textarea:focus { outline:none;border-color:var(--gold);background:var(--white); }
.form-textarea { resize:vertical;min-height:100px; }
.form-row-2 { display:grid;grid-template-columns:1fr 1fr;gap:1rem; }
.form-row-3 { display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem; }
.form-hint { font-size:.72rem;color:var(--muted);margin-top:.25rem; }

.toggle-switch { display:flex;align-items:center;gap:.75rem; }
.toggle-input { position:relative;display:inline-block;width:44px;height:24px; }
.toggle-input input { opacity:0;width:0;height:0; }
.toggle-slider {
    position:absolute;inset:0;background:#CBD5E1;
    border-radius:100px;cursor:pointer;transition:.3s;
}
.toggle-slider::before {
    content:'';position:absolute;
    width:18px;height:18px;background:#fff;
    border-radius:50%;bottom:3px;left:3px;transition:.3s;
    box-shadow:0 1px 3px rgba(0,0,0,.2);
}
.toggle-input input:checked + .toggle-slider { background:var(--gold); }
.toggle-input input:checked + .toggle-slider::before { transform:translateX(20px); }
.toggle-label { font-size:.875rem;color:var(--text);font-weight:500; }

/* Images Upload */
.images-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(90px,1fr));
    gap:.5rem;
    margin-bottom:.75rem;
}
.img-thumb {
    aspect-ratio:1;border-radius:var(--radius);overflow:hidden;
    position:relative;border:1px solid var(--border);background:#F5F0EB;
}
.img-thumb img { width:100%;height:100%;object-fit:cover; }
.img-thumb-del {
    position:absolute;top:.3rem;left:.3rem;
    width:22px;height:22px;background:#DC2626;color:#fff;
    border:none;border-radius:50%;cursor:pointer;
    display:flex;align-items:center;justify-content:center;font-size:.65rem;
}

.upload-zone {
    border:2px dashed var(--border);border-radius:var(--radius);
    padding:1.5rem;text-align:center;cursor:pointer;
    transition:all .2s;background:var(--bg);
}
.upload-zone:hover { border-color:var(--gold);background:rgba(182,138,74,.04); }
.upload-zone i { font-size:1.75rem;color:var(--muted);margin-bottom:.5rem;opacity:.5; }
.upload-zone p { font-size:.8rem;color:var(--muted); }
.upload-zone input { display:none; }

.btn-save { display:flex;align-items:center;justify-content:center;gap:.5rem;width:100%;padding:.9rem;background:var(--brown);color:var(--gold);border:none;border-radius:var(--radius);font-family:var(--font);font-size:.95rem;font-weight:700;cursor:pointer;transition:all .2s;margin-top:.5rem; }
.btn-save:hover { background:var(--gold);color:var(--brown); }

@media (max-width:900px) {
    .product-form-grid { grid-template-columns:1fr; }
    .form-row-3 { grid-template-columns:1fr 1fr; }
}
</style>
@endpush

@section('content')

<div style="margin-bottom:1.25rem;">
    <a href="{{ route('admin.products.index') }}" style="color:var(--muted);font-size:.85rem;display:inline-flex;align-items:center;gap:.4rem;text-decoration:none;">
        <i class="fa-solid fa-chevron-right fa-xs"></i> العودة للمنتجات
    </a>
</div>

<form
    action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}"
    method="POST"
    enctype="multipart/form-data"
>
    @csrf
    @if(isset($product)) @method('PUT') @endif

    <div class="product-form-grid">

        {{-- ══ Main Column ══ --}}
        <div>
            {{-- المعلومات الأساسية --}}
            <div class="form-card">
                <div class="form-card-head"><i class="fa-solid fa-circle-info fa-sm"></i> المعلومات الأساسية</div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label class="form-label">اسم المنتج <span style="color:#DC2626">*</span></label>
                        <input type="text" name="title" class="form-input"
                            value="{{ old('title', $product->title ?? '') }}" required>
                        @error('title')<div style="color:#DC2626;font-size:.75rem;margin-top:.25rem;">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">التصنيف <span style="color:#DC2626">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">اختر تصنيفاً</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                    @if($cat->parent) — {{ $cat->parent->name }} @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">وصف مختصر</label>
                        <textarea name="short_desc" class="form-textarea" style="min-height:70px;"
                            placeholder="جملة أو جملتان تصفان المنتج...">{{ old('short_desc', $product->short_desc ?? '') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">الوصف التفصيلي</label>
                        <textarea name="description" class="form-textarea">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">القصة التاريخية <span style="color:var(--muted);font-weight:400;">(اختياري)</span></label>
                        <textarea name="story" class="form-textarea" placeholder="ما قصة هذه القطعة وتاريخها؟...">{{ old('story', $product->story ?? '') }}</textarea>
                        <div class="form-hint">القصة تضيف قيمة عاطفية وتُميّز المنتج</div>
                    </div>
                </div>
            </div>

            {{-- التفاصيل --}}
            <div class="form-card">
                <div class="form-card-head"><i class="fa-solid fa-tags fa-sm"></i> تفاصيل القطعة</div>
                <div class="form-card-body">
                    <div class="form-row-2">
                        <div class="form-group">
                            <label class="form-label">الحقبة الزمنية</label>
                            <input type="text" name="era" class="form-input"
                                value="{{ old('era', $product->era ?? '') }}" placeholder="القرن التاسع عشر">
                        </div>
                        <div class="form-group">
                            <label class="form-label">بلد المنشأ</label>
                            <input type="text" name="country_of_origin" class="form-input"
                                value="{{ old('country_of_origin', $product->country_of_origin ?? '') }}" placeholder="فرنسا">
                        </div>
                    </div>
                    <div class="form-row-3">
                        <div class="form-group">
                            <label class="form-label">المادة</label>
                            <input type="text" name="material" class="form-input"
                                value="{{ old('material', $product->material ?? '') }}" placeholder="خشب جوز">
                        </div>
                        <div class="form-group">
                            <label class="form-label">الأبعاد</label>
                            <input type="text" name="dimensions" class="form-input"
                                value="{{ old('dimensions', $product->dimensions ?? '') }}" placeholder="120×60×80 سم">
                        </div>
                        <div class="form-group">
                            <label class="form-label">الحالة</label>
                            <select name="condition" class="form-select">
                                @foreach(['ممتازة','جيدة جداً','جيدة','مقبولة'] as $cond)
                                    <option {{ old('condition', $product->condition ?? '') === $cond ? 'selected' : '' }}>{{ $cond }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- الصور --}}
            <div class="form-card">
                <div class="form-card-head"><i class="fa-solid fa-images fa-sm"></i> صور المنتج</div>
                <div class="form-card-body">
                    @isset($product)
                        @if($product->images->isNotEmpty())
                            <div class="images-grid" id="existingImages">
                                @foreach($product->images as $img)
                                    <div class="img-thumb" id="img-{{ $img->id }}">
                                        <img src="{{ Storage::url($img->url) }}" alt="">
                                        <button type="button" class="img-thumb-del"
                                            onclick="deleteImage({{ $img->id }})">✕</button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endisset
                    <div class="upload-zone" onclick="document.getElementById('imagesInput').click()">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <p>اضغط لرفع صور جديدة</p>
                        <p style="font-size:.72rem;margin-top:.25rem;">JPG, PNG, WEBP — حتى 8MB لكل صورة</p>
                        <input type="file" id="imagesInput" name="images[]" multiple
                               accept=".jpg,.jpeg,.png,.webp"
                               onchange="previewImages(this)">
                    </div>
                    <div class="images-grid" id="newImagesPreview" style="margin-top:.5rem;"></div>
                </div>
            </div>
        </div>

        {{-- ══ Sidebar ══ --}}
        <div>
            {{-- التسعير --}}
            <div class="form-card">
                <div class="form-card-head"><i class="fa-solid fa-coins fa-sm"></i> التسعير والمخزون</div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label class="form-label">السعر (ر.س) <span style="color:#DC2626">*</span></label>
                        <input type="number" name="price" class="form-input"
                            value="{{ old('price', $product->price ?? '') }}"
                            step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">السعر قبل الخصم</label>
                        <input type="number" name="compare_at_price" class="form-input"
                            value="{{ old('compare_at_price', $product->compare_at_price ?? '') }}"
                            step="0.01" min="0">
                        <div class="form-hint">اتركه فارغاً إن لم يكن هناك خصم</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">المخزون <span style="color:#DC2626">*</span></label>
                        <input type="number" name="stock" class="form-input"
                            value="{{ old('stock', $product->stock ?? 1) }}"
                            min="0" required>
                    </div>
                </div>
            </div>

            {{-- الحالة --}}
            <div class="form-card">
                <div class="form-card-head"><i class="fa-solid fa-toggle-on fa-sm"></i> الحالة والخصائص</div>
                <div class="form-card-body" style="display:flex;flex-direction:column;gap:1rem;">
                    @foreach([
                        ['is_active',   'نشط وظاهر في المتجر',    $product->is_active   ?? true],
                        ['is_featured', 'منتج مميز (يظهر في الرئيسية)', $product->is_featured ?? false],
                        ['is_rare',     'قطعة نادرة',             $product->is_rare     ?? false],
                    ] as [$name, $label, $checked])
                        <div class="toggle-switch">
                            <label class="toggle-input">
                                <input type="hidden" name="{{ $name }}" value="0">
                                <input type="checkbox" name="{{ $name }}" value="1"
                                    {{ old($name, $checked) ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn-save">
                <i class="fa-solid fa-floppy-disk fa-sm"></i>
                {{ isset($product) ? 'حفظ التعديلات' : 'إضافة المنتج' }}
            </button>

            @isset($product)
                <div style="margin-top:.75rem;text-align:center;">
                    <a href="{{ route('product.show', $product->slug) }}" target="_blank"
                       style="font-size:.8rem;color:var(--muted);text-decoration:none;display:inline-flex;align-items:center;gap:.3rem;">
                        <i class="fa-solid fa-arrow-up-right-from-square fa-xs"></i>
                        معاينة في الموقع
                    </a>
                </div>
            @endisset
        </div>

    </div>
</form>
@endsection

@push('scripts')
<script>
function previewImages(input) {
    const preview = document.getElementById('newImagesPreview');
    preview.innerHTML = '';
    Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'img-thumb';
            div.innerHTML = `<img src="${e.target.result}" alt="">`;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

function deleteImage(id) {
    if (!confirm('حذف هذه الصورة؟')) return;
    fetch(`/admin/products/images/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('img-' + id)?.remove();
        }
    });
}
</script>
@endpush
