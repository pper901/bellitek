<?php


namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('images','user');


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
        'type' => 'required|string',
        'category' => 'required|string',
        'brand' => 'nullable|string',
        'name' => 'required|string',
        'condition' => 'required|string',
        'stock' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'specification' => 'nullable|string',
        'content' => 'nullable|string',
        'images.*' => 'nullable|image|max:5120',
        ]);


        $product = Product::create($data);


        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
            $path = $file->store('products','public');
            ProductImage::create(['product_id' => $product->id,'path' => $path]);
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
        'name' => 'required|string',
        'condition' => 'required|string',
        'stock' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'specification' => 'nullable|string',
        'content' => 'nullable|string',
        'images.*' => 'nullable|image|max:5120',
        ]);


        $product->update($data);


        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('products','public');
                ProductImage::create(['product_id' => $product->id,'path' => $path]);
            }
        }


        return redirect()->route('admin.products.index')->with('success','Product updated');
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
}