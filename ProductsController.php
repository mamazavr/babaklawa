<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\CreateProductRequest;
use App\Http\Requests\Products\EditProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::with('categories')->sortable()->paginate(10);
        return view('admin/products/index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin/products/create', compact('categories'));
    }

    public function store(CreateProductRequest $request)
    {
        $data = $this->processProductData($request);
        $product = Product::create($data);
        $product->categories()->attach($request->input('categories'));
        return $this->redirectWithSuccessMessage($product, 'created');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin/products/edit', compact('product', 'categories'));
    }

    public function update(EditProductRequest $request, Product $product)
    {
        $data = $this->processProductData($request, $product);
        $product->update($data);
        $product->categories()->sync($request->input('categories'));
        return $this->redirectWithSuccessMessage($product, 'updated');
    }

    public function destroy(Product $product)
    {
        $this->deleteProductImages($product);
        $product->categories()->detach();
        $product->delete();
        return $this->redirectWithSuccessMessage($product, 'deleted');
    }

    private function processProductData(Request $request, Product $product = null)
    {
        $data = $request->validated();

        if ($request->hasFile('main_image')) {
            if ($product) {
                Storage::disk('public')->delete($product->main_image);
            }
            $mainImage = $request->file('main_image');
            $mainImagePath = $mainImage->store('products', 'public');
            $data['main_image'] = $mainImagePath;
        }

        if ($request->hasFile('gallery')) {
            if ($product) {
                foreach ($product->gallery as $galleryImage) {
                    Storage::disk('public')->delete($galleryImage);
                }
            }
            $galleryImages = $request->file('gallery');
            $galleryPaths = [];
            foreach ($galleryImages as $galleryImage) {
                $galleryPath = $galleryImage->store('products/gallery', 'public');
                $galleryPaths[] = $galleryPath;
            }
            $data['gallery'] = $galleryPaths;
        }

        return $data;
    }

    private function deleteProductImages(Product $product)
    {
        Storage::disk('public')->delete($product->main_image);
        foreach ($product->gallery as $galleryImage) {
            Storage::disk('public')->delete($galleryImage);
        }
    }

    private function redirectWithSuccessMessage($product, $action)
    {
        notify()->success("Product '{$product->title}' was {$action}!");
        return redirect()->route('admin.products.index');
    }
}
