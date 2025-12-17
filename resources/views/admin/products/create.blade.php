@extends('admin.layout')

@section('content')

<h1 class="text-3xl font-bold mb-6">Create New Product</h1>

<form action="{{ route('admin.products.store') }}" method="POST"
      enctype="multipart/form-data" class="space-y-6">
@csrf

<div class="grid grid-cols-2 gap-4">

    <div>
        <label>Product Type</label>
        <select name="type" class="input w-full">
            <option value="tool">Tool</option>
            <option value="part">Part</option>
            <option value="device">Device</option>
        </select>
    </div>

    <div>
        <label>Category</label>
        <input name="category" class="input w-full"
               placeholder="e.g soldering tools / android phone">
    </div>

    <div>
        <label>Brand</label>
        <input name="brand" class="input w-full" placeholder="Samsung / HP / iPhone">
    </div>

    <div>
        <label>Condition</label>
        <select name="condition" class="input w-full">
            <option value="new">New</option>
            <option value="fairly_used">Fairly Used</option>
        </select>
    </div>
    
    <div>
        <label>Purchase Price (₦)</label>
        <input type="number" name="purchase_price" class="input w-full" placeholder="50000">
    </div>

    <div>
        <label>Price (₦)</label>
        <input type="number" name="price" class="input w-full" placeholder="50000">
    </div>

    <div>
        <label>Quantity</label>
        <input type="number" name="quantity" class="input w-full" value="1">
    </div>
    
    <div class="mb-3">
        <label for="weight">Weight (kg)</label>
        <input type="number" step="0.01" class="form-control" name="weight" id="weight" required>
    </div>

    <div class="col-span-2">
        <label>Product Name</label>
        <input name="name" class="input w-full" placeholder="e.g iPhone 12 Pro">
    </div>

    <div class="col-span-2">
        <label>Slug (optional)</label>
        <input name="slug" class="input w-full" placeholder="iphone-12-pro">
    </div>

    <div class="col-span-2">
        <label>Images (multiple allowed)</label>
        <input type="file" name="images[]" multiple class="input w-full" id="imageUpload">

        <div id="preview" class="flex gap-2 mt-2"></div>
    </div>

    <div class="col-span-2">
        <label>Description</label>
        <textarea name="description" class="input w-full h-32"></textarea>
    </div>

    <div class="col-span-2">
        <label>Specification</label>
        <textarea name="specification" class="input w-full h-32"></textarea>
    </div>

    <div class="col-span-2">
        <label>Package Content</label>
        <textarea name="content" class="input w-full h-32"></textarea>
    </div>

</div>

<button class="bg-blue-600 text-white px-4 py-2 rounded">
    Save Product
</button>

</form>


<script>
// Image preview
document.getElementById('imageUpload').addEventListener('change', function(event) {
    let preview = document.getElementById('preview');
    preview.innerHTML = "";

    Array.from(event.target.files).forEach(file => {
        let reader = new FileReader();
        reader.onload = (e) => {
            let img = document.createElement('img');
            img.src = e.target.result;
            img.className = "w-20 h-20 object-cover rounded";
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});
</script>

@endsection
