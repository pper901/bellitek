<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; 
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($search = $request->query('search')) {
            $query->where('name','like',"%{$search}%");
        }

        if ($status = $request->query('status')) {
            $query->where('status',$status);
        }

        if ($type = $request->query('type')) {
            $query->where('type',$type);
        }

        if ($category = $request->query('category')) {
            $query->where('category',$category);
        }

        if ($request->query('trashed') === 'true') {
            $query->onlyTrashed();
        }

        $products = $query->latest()->paginate(20)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type'            => 'required|string',
            'category'        => 'required|string',
            'brand'           => 'nullable|string',
            'name'            => 'required|string|max:255',
            'condition'       => 'required|string',
            'quantity'        => 'required|integer|min:0',
            'price'           => 'required|numeric|min:0',
            'purchase_price'  => 'required|numeric|min:0',
            'weight'          => 'required|numeric|min:0.01',
            'description'     => 'nullable|string',
            'specification'   => 'nullable|string',
            'content'         => 'nullable|string',
            'slug'            => 'nullable|string|max:255|unique:products,slug',
            'images'          => 'nullable|array',
            'images.*'        => 'image|max:5120',
        ]);

        // FIX 1: Map form field 'quantity' to database field 'stock'
        $data['stock'] = $data['quantity'];
        unset($data['quantity']);

        // Handle slug creation if not provided
        $data['slug'] = $data['slug'] ?? generateUniqueSlug($data['name'], $product->id ?? null);
        
        // FIX 2: Since the database column is TEXT, we store the raw string input.
        // We still use null coalescing just in case the key is missing from validation result.
        $specification_data = $data['specification'] ?? null;


        // Ensure all required database fields are present and correctly named.
        $product = Product::create([
            'type' => $data['type'],
            'category' => $data['category'],
            'brand' => $data['brand'],
            'name' => $data['name'],
            'slug' => $data['slug'],
            'condition' => $data['condition'],
            'stock' => $data['stock'], // CORRECTED KEY: 'stock' (matches migration)
            'price' => $data['price'],
            'purchase_price' => $data['purchase_price'],
            'weight' => $data['weight'],
            'description' => $data['description'],
            'specification' => $specification_data, // Plain string (matches migration TEXT type)
            'content' => $data['content'],
            'status' => 'available', // Use 'available' as per your ENUM definition
        ]);

        // Upload images to Cloudinary
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $upload = cloudinary()->upload(
                    $file->getRealPath(),
                    ['folder' => 'products']
                );

                ProductImage::create([
                    'public_id' => $upload->getPublicId(),
                    'path' => $upload->getSecurePath(), // HTTPS CDN URL
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success','Product created');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'type' => 'required|string',
            'category' => 'required|string',
            'brand' => 'nullable|string',
            'name' => 'required|string|max:255',
            'condition' => 'required|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'specification' => 'nullable|string',
            'content' => 'nullable|string',
            'status' => 'required|in:available,in_cart,sold',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,

            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',

            'remove_images' => 'nullable|array',
            'remove_images.*' => 'integer|exists:product_images,id',
        ]);

        DB::transaction(function () use ($request, $product, $data) {

            // Quantity → Stock
            $data['stock'] = $data['quantity'];
            unset($data['quantity']);

            // Slug
            $data['slug'] = $data['slug'] ?? generateUniqueSlug($data['name'], $product->id ?? null);


            // Update product
            $product->update($data);

            // Delete images
            if ($request->filled('remove_images')) {
                $images = $product->images()
                    ->whereIn('id', $request->remove_images)
                    ->get();

                foreach ($images as $image) {
                    
                cloudinary()->destroy($image->public_id);
                    $image->delete();
                }
            }

            // Upload new images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $upload = cloudinary()->upload(
                        $file->getRealPath(),
                        ['folder' => 'products']
                    );

                    $product->images()->create([
                        'path'      => $upload->getSecurePath(),
                        'public_id' => $upload->getPublicId(),
                    ]);
                }
            }
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully');
    }

    public function reviews(Product $product) 
    {
        $reviews = $product->reviews()->with('user')->latest()->paginate(10);
        return view('admin.products.reviews', compact('product', 'reviews'));
    }

    public function destroyReview(Review $review) 
    {
        $review->delete();
        return back()->with('success', 'Review deleted successfully');
    }

    public function deleteImage(ProductImage $image)
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();

        return back()->with('success', 'Image removed');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success','Product soft-deleted');
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();
        return back()->with('success','Product restored');
    }

    
    function generateUniqueSlug($name, $productId = null) 
    {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 1;

        while (Product::where('slug', $slug)
            ->when($productId, fn($query) => $query->where('id', '!=', $productId))
            ->exists()) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }

}