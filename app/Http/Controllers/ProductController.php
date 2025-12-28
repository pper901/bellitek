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
use Illuminate\Support\Facades\Http;

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
            'slug'            => 'nullable|string|max:255',
            'images'          => 'nullable|array',
            'images.*'        => 'image|max:5120',
        ]);

        // Map quantity → stock
        $data['stock'] = $data['quantity'];
        unset($data['quantity']);

        // Generate unique slug whether user typed it or not
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['name']);


        $specification_data = $data['specification'] ?? null;

        DB::transaction(function () use ($data, $request, &$product, $specification_data) {
            // Create the product
            $product = Product::create([
                'type'           => $data['type'],
                'category'       => $data['category'],
                'brand'          => $data['brand'],
                'name'           => $data['name'],
                'slug'           => $data['slug'],
                'condition'      => $data['condition'],
                'stock'          => $data['stock'],
                'price'          => $data['price'],
                'purchase_price' => $data['purchase_price'],
                'weight'         => $data['weight'],
                'description'    => $data['description'],
                'specification'  => $specification_data,
                'content'        => $data['content'],
                'status'         => 'available',
            ]);

            // Upload images to Cloudinary
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    // Calls the updated helper with the .net domain
                    $upload = $this->uploadToUploadcare($file);

                    $product->images()->create([
                        'public_id' => $upload['uuid'],
                        'path'      => $upload['url'],
                    ]);
                }
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
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
                $data['stock'] = $data['quantity'];
                unset($data['quantity']);
                $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['name'], $product->id);

                $product->update($data);

                // --- NEW SDK DELETION LOGIC ---
                if ($request->filled('remove_images')) {
                    $api = \Uploadcare\Api::create(
                        config('services.uploadcare.public'), 
                        config('services.uploadcare.secret')
                    );

                    $images = $product->images()->whereIn('id', $request->remove_images)->get();

                    foreach ($images as $image) {
                        try {
                            // Delete from Uploadcare servers using the stored public_id (UUID)
                            $api->file()->file($image->public_id)->delete();
                        } catch (\Exception $e) {
                            \Log::error("Uploadcare deletion failed: " . $e->getMessage());
                        }
                        $image->delete();
                    }
                }

                // --- NEW UPLOADCARE UPLOAD LOGIC ---
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $file) {
                        $upload = $this->uploadToUploadcare($file);
                        $product->images()->create([
                            'public_id' => $upload['uuid'],
                            'path'      => $upload['url'],
                        ]);
                    }
                }
            });

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
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

    
    private function uploadToUploadcare($file)
    {
        $api = \Uploadcare\Api::create(
            config('services.uploadcare.public'), 
            config('services.uploadcare.secret')
        );

        // Use fromContent to bypass argument type errors
        $uploadcareFile = $api->uploader()->fromContent(
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName(),
            $file->getMimeType()
        );

        // Explicitly store to avoid 404s
        $uploadcareFile->store();

        $uuid = $uploadcareFile->getUuid();

        // Use your specific account domain and the preview transformation
        $url = "https://5to6o2z4j5.ucarecd.net/{$uuid}/-/preview/500x500/";

        return [
            'uuid' => $uuid,
            'url'  => $url,
        ];
    }

    /**
     * Generates a unique slug for a product.
     *
     * @param string $name
     * @param int|null $currentId (Optional) Used to ignore the current product during update
     * @return string
     */
    private function generateUniqueSlug($name, $currentId = null)
    {
        // 1. Create the base slug from the name
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        // 2. Check if the slug already exists in the database
        // If it's an update, we ignore the record with the $currentId
        while (\App\Models\Product::where('slug', $slug)
                ->when($currentId, function ($query, $currentId) {
                    return $query->where('id', '!=', $currentId);
                })
                ->exists()) 
        {
            // 3. If it exists, append a number and check again
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }


}