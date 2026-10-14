@extends('feimso.layout')
@section('title', 'Create listing')

@section('content')
<div class="container py-4" style="max-width:720px">
    <h1 style="font-size:22px;font-weight:700;margin-bottom:6px">Create a listing</h1>
    <p style="font-size:14px;color:var(--fm-muted);margin-bottom:28px">Choose what you'd like to list and fill in the details.</p>

    @if($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="font-size:13px">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('feimso.listing.store') }}" enctype="multipart/form-data" id="create-listing-form">
        @csrf

        {{-- Listing type --}}
        <div class="mb-4">
            <label style="font-size:13px;font-weight:600;margin-bottom:12px;display:block">Listing type</label>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                @foreach(['sale'=>'For sale','rental'=>'For rent','service'=>'Service','experience'=>'Experience'] as $val=>$label)
                <label style="cursor:pointer">
                    <input type="radio" name="listing_type" value="{{ $val }}" style="display:none"
                           class="listing-type-radio" {{ old('listing_type','sale')===$val?'checked':'' }}>
                    <span class="fm-chip {{ old('listing_type','sale')===$val?'selected':'' }}" style="padding:9px 18px;font-size:14px;font-weight:500;border-radius:8px">{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Photos --}}
        <div class="mb-4">
            <label style="font-size:13px;font-weight:600;margin-bottom:8px;display:block">Photos <span style="font-weight:400;color:var(--fm-muted)">(up to 8)</span></label>
            <div style="border:2px dashed var(--fm-border);border-radius:10px;padding:24px;text-align:center;cursor:pointer" onclick="document.getElementById('photos-input').click()">
                <svg width="32" height="32" fill="none" stroke="#9ca3af" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 10px;display:block"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                <div style="font-size:14px;font-weight:500">Click to upload photos</div>
                <div style="font-size:12px;color:var(--fm-muted);margin-top:4px">JPG, PNG or WEBP, max 8MB each</div>
            </div>
            <input type="file" id="photos-input" name="photos[]" multiple accept="image/*" style="display:none">
            <div id="photo-previews" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:10px"></div>
        </div>

        {{-- Basic fields --}}
        <div class="row g-3 mb-3">
            <div class="col-12">
                <label class="fm-label">Title</label>
                <input type="text" name="title" class="fm-input" value="{{ old('title') }}"
                       placeholder="E.g. Chanel Vintage Tweed Blazer" required maxlength="120">
            </div>
        </div>
        <div class="mb-3">
            <label class="fm-label">Description</label>
            <textarea name="description" class="fm-input" rows="4" style="resize:vertical"
                      placeholder="Describe the item — condition, brand, measurements, why you're selling…">{{ old('description') }}</textarea>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-6">
                <label class="fm-label">Category</label>
                <select name="category" class="fm-input">
                    <option value="">Select…</option>
                    @foreach(['Dresses','Tops','Bottoms','Outerwear','Shoes','Bags','Accessories','Activewear','Swimwear','Formal','Jewellery','Other'] as $c)
                    <option value="{{ $c }}" {{ old('category')==$c?'selected':'' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6">
                <label class="fm-label">Gender</label>
                <select name="gender_target" class="fm-input">
                    <option value="">Select…</option>
                    @foreach(['Womenswear','Menswear','Childrenswear','Unisex'] as $g)
                    <option value="{{ $g }}" {{ old('gender_target')==$g?'selected':'' }}>{{ $g }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Sale / rental fields --}}
        <div id="fields-sale-rental">
            <div class="row g-3 mb-3">
                <div class="col-4">
                    <label class="fm-label">Price (AED)</label>
                    <input type="number" name="price" class="fm-input" value="{{ old('price') }}" min="0" step="0.01" placeholder="0.00">
                </div>
                <div class="col-4">
                    <label class="fm-label">Size</label>
                    <input type="text" name="size" class="fm-input" value="{{ old('size') }}" placeholder="E.g. M, 10, UK8">
                </div>
                <div class="col-4">
                    <label class="fm-label">Condition</label>
                    <select name="condition" class="fm-input">
                        <option value="">Select…</option>
                        @foreach(['new_with_tags'=>'New with tags','excellent'=>'Excellent','very_good'=>'Very good','good'=>'Good','fair'=>'Fair'] as $v=>$l)
                        <option value="{{ $v }}" {{ old('condition')==$v?'selected':'' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="fm-label">Brand</label>
                    <input type="text" name="brand" class="fm-input" value="{{ old('brand') }}" placeholder="E.g. Zara, Chanel">
                </div>
                <div class="col-6">
                    <label class="fm-label">Colour</label>
                    <input type="text" name="colour" class="fm-input" value="{{ old('colour') }}" placeholder="E.g. Navy blue">
                </div>
            </div>
        </div>

        {{-- Rental-specific --}}
        <div id="fields-rental" style="display:none">
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="fm-label">Price per day (AED)</label>
                    <input type="number" name="price_per_day" class="fm-input" value="{{ old('price_per_day') }}" min="0" step="0.01">
                </div>
                <div class="col-6">
                    <label class="fm-label">Security deposit (AED)</label>
                    <input type="number" name="deposit" class="fm-input" value="{{ old('deposit') }}" min="0" step="0.01">
                </div>
            </div>
            <div class="mb-3">
                <label class="fm-label">Pickup / delivery type</label>
                <select name="pickup_type" class="fm-input">
                    @foreach(['pickup'=>'Pickup only','delivery'=>'Delivery available','both'=>'Both available'] as $v=>$l)
                    <option value="{{ $v }}">{{ $l }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Experience-specific --}}
        <div id="fields-experience" style="display:none">
            <div class="row g-3 mb-3">
                <div class="col-4">
                    <label class="fm-label">Price (AED)</label>
                    <input type="number" name="experience_price" class="fm-input" value="{{ old('experience_price') }}" min="0" step="0.01">
                </div>
                <div class="col-4">
                    <label class="fm-label">Duration (min)</label>
                    <input type="number" name="duration_minutes" class="fm-input" value="{{ old('duration_minutes') }}" min="30" step="15">
                </div>
                <div class="col-4">
                    <label class="fm-label">Max participants</label>
                    <input type="number" name="max_participants" class="fm-input" value="{{ old('max_participants') }}" min="1">
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="fm-label">Available from</label>
                    <input type="date" name="available_from" class="fm-input" value="{{ old('available_from') }}">
                </div>
                <div class="col-6">
                    <label class="fm-label">Available to</label>
                    <input type="date" name="available_to" class="fm-input" value="{{ old('available_to') }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="fm-label">Location type</label>
                <select name="location_type" class="fm-input">
                    @foreach(['in_person'=>'In person','virtual'=>'Virtual','hybrid'=>'Hybrid'] as $v=>$l)
                    <option value="{{ $v }}">{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="fm-label">Scope of work / what's included</label>
                <textarea name="scope_of_work" class="fm-input" rows="3" style="resize:vertical" placeholder="Describe what participants will experience…">{{ old('scope_of_work') }}</textarea>
            </div>
        </div>

        {{-- Delivery & common --}}
        <div class="row g-3 mb-3">
            <div class="col-6">
                <label class="fm-label">Delivery option</label>
                <select name="delivery_option" class="fm-input">
                    @foreach(['ship_only'=>'Ship only','local_only'=>'Local pickup only','both'=>'Ship + Local pickup'] as $v=>$l)
                    <option value="{{ $v }}" {{ old('delivery_option')==$v?'selected':'' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6">
                <label class="fm-label">Cancellation policy</label>
                <select name="cancellation_policy" class="fm-input">
                    @foreach(['flexible'=>'Flexible','moderate'=>'Moderate','strict'=>'Strict','no_refund'=>'No refund'] as $v=>$l)
                    <option value="{{ $v }}" {{ old('cancellation_policy')==$v?'selected':'' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer">
                <input type="checkbox" name="authenticity_declared" value="1" {{ old('authenticity_declared') ? 'checked':'' }}>
                I confirm this item is authentic and accurately described
            </label>
        </div>

        <div style="display:flex;gap:12px;margin-top:24px">
            <button type="submit" name="publish" value="1" class="fm-btn" style="flex:1">Publish listing</button>
            <button type="submit" name="draft" value="1" class="fm-btn-outline" style="flex:1;width:auto;border:1px solid var(--fm-border);padding:12px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;background:#fff;color:var(--fm-dark)">Save as draft</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Toggle listing type fields
const radios = document.querySelectorAll('.listing-type-radio');
const chips = document.querySelectorAll('label:has(.listing-type-radio) .fm-chip');
const fieldsRental = document.getElementById('fields-rental');
const fieldsExperience = document.getElementById('fields-experience');
const fieldsSaleRental = document.getElementById('fields-sale-rental');

radios.forEach((radio, i) => {
    radio.addEventListener('change', () => {
        // Toggle chip styles
        document.querySelectorAll('label:has(.listing-type-radio) .fm-chip').forEach(c => c.classList.remove('selected'));
        chips[i].classList.add('selected');
        // Toggle fields
        const val = radio.value;
        fieldsSaleRental.style.display = val === 'experience' ? 'none' : 'block';
        fieldsRental.style.display = val === 'rental' ? 'block' : 'none';
        fieldsExperience.style.display = val === 'experience' ? 'block' : 'none';
    });
});

// Trigger on load
const checked = document.querySelector('.listing-type-radio:checked');
if (checked) checked.dispatchEvent(new Event('change'));

// Photo preview
document.getElementById('photos-input').addEventListener('change', function() {
    const container = document.getElementById('photo-previews');
    container.innerHTML = '';
    Array.from(this.files).slice(0,8).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.cssText = 'width:80px;height:80px;object-fit:cover;border-radius:6px;border:1px solid var(--fm-border)';
            container.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush
