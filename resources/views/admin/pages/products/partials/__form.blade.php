<div class="mb-3">
    <label>Title</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $product->title ?? '') }}">
</div>

<div class="mb-3">
    <label>Slug</label>
    <input type="text" name="slug" class="form-control" value="{{ old('slug', $product->slug ?? '') }}">
</div>

<div class="mb-3">
    <label>Regular Price</label>
    <input type="number" name="regular_price" step="0.01" class="form-control" value="{{ old('regular_price', $product->regular_price ?? '') }}">
</div>

<div class="mb-3">
    <label>Sale Price</label>
    <input type="number" name="sale_price" step="0.01" class="form-control" value="{{ old('sale_price', $product->sale_price ?? '') }}">
</div>

<div class="mb-3">
    <label>Discount</label>
    <input type="number" name="discount" step="0.01" class="form-control" value="{{ old('discount', $product->discount ?? '') }}">
</div>

<div class="mb-3">
    <label>Image</label>
    <input type="file" name="images" class="form-control">
    @if (!empty($product->images))
        <img src="{{ asset('storage/' . $product->images) }}" alt="product" width="80" class="mt-2">
    @endif
</div>

<div class="mb-3">
    <label>Points</label>
    <input type="number" name="points" class="form-control" value="{{ old('points', $product->points ?? '') }}">
</div>

<div class="mb-3">
    <label>Description</label>
    <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label>Stock</label>
    <select name="stock" class="form-control">
        <option value="1" {{ (old('stock', $product->stock ?? 1) == 1) ? 'selected' : '' }}>In Stock</option>
        <option value="0" {{ (old('stock', $product->stock ?? 1) == 0) ? 'selected' : '' }}>Out of Stock</option>
    </select>
</div>

<div class="mb-3">
    <label>Status</label>
    <select name="status" class="form-control">
        <option value="1" {{ (old('status', $product->status ?? 1) == 1) ? 'selected' : '' }}>Active</option>
        <option value="0" {{ (old('status', $product->status ?? 1) == 0) ? 'selected' : '' }}>Inactive</option>
    </select>
</div>
