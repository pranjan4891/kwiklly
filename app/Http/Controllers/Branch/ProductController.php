<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Attribute;
use App\Models\ProductVariant;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    //
    public function index()
    {
        $title = 'Product List';
       $branch= Auth::guard('branch')->user();
        if (!$branch) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $products = Product::with(['category', 'subcategory', 'vendor', 'featureImage'])->where('vendor_id', $branch->id)->get();
        return view('branch.product.index', compact('products', 'title', 'branch'));
    }

    public function create()
    {
        $title = 'Add Product';
       $branch= Auth::guard('branch')->user();
        if (!$branch) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $categories = Category::all();
        $images = ProductImages::all();
        $attributes = Attribute::all();
        return view('branch.product.create', compact('title', 'branch', 'categories', 'images', 'attributes'));
    }

    public function getByCategory(Request $request)
    {
        $subs = Subcategory::where('category_id', $request->category_id)->get();
        return response()->json($subs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:subcategories,id',
            'feature_image_id' => 'required|exists:product_images,id',
            'cgst' => 'nullable|numeric|min:0',
            'sgst' => 'nullable|numeric|min:0',
            'best_offers' => 'nullable|boolean',
            'top_selling' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ], [
            'category_id.required' => 'The category is required.',
            'sub_category_id.required' => 'The subcategory is required.',
            'feature_image_id.required' => 'Please select a feature image or upload a new one.',
        ]);

        $product = new Product();
        $product->vendor_id = auth()->guard('branch')->id();
        $product->sku = strtoupper(Str::random(10)); // Generate a random SKU
        $product->title = $request->title;
        $product->sub_title = $request->sub_title;
        $product->slug = Str::slug($request->title . '-' . uniqid());
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->is_physical = $request->has('is_physical');
        $product->description = $request->description;
        $product->disclaimer = $request->disclaimer;
        $product->information = $request->information;
        $product->cgst = $request->cgst ?? 0;
        $product->sgst = $request->sgst ?? 0;
        $product->feature_image_id = $request->feature_image_id;
        $product->best_offers = $request->has('best_offers');
        $product->top_selling = $request->has('top_selling');
        $product->is_active = $request->has('is_active');
        $product->is_deleted = 0;

        $product->save();

        return redirect()->route('admin.products')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $title = 'Edit Product';
       $branch= Auth::guard('branch')->user();
        if (!$branch) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $product = Product::with(['category', 'subcategory', 'featureImage'])->findOrFail($id);
        $categories = Category::all();
        $subcategories = Subcategory::where('category_id', $product->category_id)->get();
        if ($product->sub_category_id) {
            $subcategories = Subcategory::where('id', $product->sub_category_id)->get();
        }
        $images = ProductImages::all();
        $attributes = Attribute::all();

        return view('branch.product.edit', compact('title', 'branch', 'product', 'categories', 'images', 'attributes', 'subcategories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:subcategories,id',
            'feature_image_id' => 'required|exists:product_images,id',
            'cgst' => 'nullable|numeric|min:0',
            'sgst' => 'nullable|numeric|min:0',
            'best_offers' => 'nullable|boolean',
            'top_selling' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ], [
            'category_id.exists' => 'The selected category does not exist.',
            'sub_category_id.exists' => 'The selected subcategory does not exist.',
            'feature_image_id.exists' => 'The selected feature image does not exist.',
        ]);

        $product = Product::findOrFail($id);
        $product->title = $request->title;
        $product->sub_title = $request->sub_title;
        $product->slug = Str::slug($request->title . '-' . uniqid());
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->is_physical = $request->has('is_physical');
        $product->description = $request->description;
        $product->disclaimer = $request->disclaimer;
        $product->information = $request->information;
        $product->cgst = $request->cgst ?? 0;
        $product->sgst = $request->sgst ?? 0;
        $product->feature_image_id = $request->feature_image_id;
        $product->best_offers = $request->has('best_offers');
        $product->top_selling = $request->has('top_selling');
        $product->is_active = $request->has('is_active');

        $product->save();

        return redirect()->route('admin.products')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->is_deleted = 1; // Soft delete
        $product->save();

        return redirect()->route('admin.products')->with('success', 'Product deleted successfully.');
    }

    public function createVariant($productId)
    {
        $title = 'Add Product Variant';
       $branch= Auth::guard('branch')->user();
        if (!$branch) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $product = Product::with('subcategory')->findOrFail($productId);
        $subcategoryId = $product->sub_category_id;

        // Load attributes linked to the subcategory, and their values
        $attributes = Attribute::whereIn('id', function ($query) use ($subcategoryId) {
            $query->select('attribute_id')
                ->from('attribute_subcategory')
                ->where('subcategory_id', $subcategoryId);
        })->with('values')->get(); // Use the same name as in Blade

        return view('branch.product.variants.create', compact('title', 'branch', 'product', 'attributes'));
    }


    public function storeVariant(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_name' => 'required|string|max:255',
            'variant_actual_price' => 'required|numeric',
            'variant_selling_price' => 'required|numeric',
            'stock' => 'required|integer',
            'attributes' => 'required|array',
        ]);

        $attributes = $request->input('attributes');

        $savePriceRs = $request->variant_actual_price - $request->variant_selling_price;
        $savePricePercent = ($savePriceRs / $request->variant_actual_price) * 100;

        ProductVariant::create([
            'product_id' => $request->product_id,
            'variant_name' => $request->variant_name,
            'variant_actual_price' => $request->variant_actual_price,
            'variant_selling_price' => $request->variant_selling_price,
            'variant_save_price_in_rs' => $savePriceRs,
            'variant_save_price_in_percent' => $savePricePercent,
            'stock' => $request->stock,
            'attributes' => json_encode($attributes),
        ]);

        return redirect()->back()->with('success', 'Variant created successfully.');
    }

    public function editVariant($id)
    {
        $title = 'Edit Product Variant';
       $branch= Auth::guard('branch')->user();
        if (!$branch) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $variant = ProductVariant::findOrFail($id);
        $product = Product::findOrFail($variant->product_id);

        $subcategoryId = $product->sub_category_id;
        $attributes = Attribute::whereIn('id', function ($query) use ($subcategoryId) {
            $query->select('attribute_id')
              ->from('attribute_subcategory')
              ->where('subcategory_id', $subcategoryId);
        })->with('values')->get();

        return view('branch.product.variants.edit', compact('title', 'branch', 'variant', 'product', 'attributes'));
    }

    public function updateVariant(Request $request, $id)
    {
        $variant = ProductVariant::findOrFail($id);

        $request->validate([
            'variant_name' => 'required|string',
            'variant_actual_price' => 'required|numeric',
            'variant_selling_price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $savePriceRs = $request->variant_actual_price - $request->variant_selling_price;
        $savePercent = ($savePriceRs / $request->variant_actual_price) * 100;

        // Optional: ensure attributes is always array
        $attributes = $request->input('attributes', []);

        $variant->update([
            'variant_name' => $request->variant_name,
            'variant_actual_price' => $request->variant_actual_price,
            'variant_selling_price' => $request->variant_selling_price,
            'variant_save_price_in_rs' => $savePriceRs,
            'variant_save_price_in_percent' => $savePercent,
            'stock' => $request->stock,
            'attributes' => json_encode($attributes),
        ]);

        return redirect()->route('product.variant.create', $variant->product_id)->with('success', 'Variant updated successfully.');
    }


    public function deleteVariant($id)
    {
        $variant = ProductVariant::findOrFail($id);
        $variant->delete();

        return redirect()->back()->with('success', 'Variant deleted successfully.');
    }

    public function exportProducts()
    {
        $filename = 'products_export_' . now()->format('Ymd_His') . '.csv';
        $products = Product::with('variants')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($products) {
            $handle = fopen('php://output', 'w');

            // CSV Header
            fputcsv($handle, [
                'sku', 'title', 'sub_title', 'category_id', 'sub_category_id', 'is_physical', 'description',
                'disclaimer', 'information', 'cgst', 'sgst', 'feature_image_id', 'best_offers', 'top_selling', 'is_active',
                'variant_name', 'variant_actual_price', 'variant_selling_price', 'stock', 'attributes'
            ]);

            foreach ($products as $product) {
                if ($product->variants->isEmpty()) {
                    // Product without variants
                    fputcsv($handle, [
                        $product->sku,
                        $product->title,
                        $product->sub_title,
                        $product->category_id,
                        $product->sub_category_id,
                        $product->is_physical,
                        $product->description,
                        $product->disclaimer,
                        $product->information,
                        $product->cgst,
                        $product->sgst,
                        $product->feature_image_id,
                        $product->best_offers,
                        $product->top_selling,
                        $product->is_active,
                        '', '', '', '', ''
                    ]);
                } else {
                    // One row per variant
                    foreach ($product->variants as $variant) {
                        fputcsv($handle, [
                            $product->sku,
                            $product->title,
                            $product->sub_title,
                            $product->category_id,
                            $product->sub_category_id,
                            $product->is_physical,
                            $product->description,
                            $product->disclaimer,
                            $product->information,
                            $product->cgst,
                            $product->sgst,
                            $product->feature_image_id,
                            $product->best_offers,
                            $product->top_selling,
                            $product->is_active,
                            $variant->variant_name,
                            $variant->variant_actual_price,
                            $variant->variant_selling_price,
                            $variant->stock,
                            $variant->attributes,
                        ]);
                    }
                }
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importProductsCsv()
    {
        $title = 'Product List';
       $branch= Auth::guard('branch')->user();
        if (!$branch) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }

        return view('branch.product.import', compact('title', 'branch'));
    }
    public function importProducts(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $header = array_map('trim', $data[0]);
        unset($data[0]);

        DB::beginTransaction();
        try {
            foreach ($data as $row) {
                $row = array_combine($header, $row);

                // Insert Product
                $product = Product::create([
                    'vendor_id' => Auth::guard('branch')->id(),
                    'sku' => $row['sku'] ?? strtoupper(Str::random(10)),
                    'title' => $row['title'],
                    'sub_title' => $row['sub_title'] ?? null,
                    'slug' => Str::slug($row['title'] . '-' . uniqid()),
                    'category_id' => $row['category_id'],
                    'sub_category_id' => $row['sub_category_id'] ?? null,
                    'is_physical' => $row['is_physical'] ?? 1,
                    'description' => $row['description'] ?? null,
                    'disclaimer' => $row['disclaimer'] ?? null,
                    'information' => $row['information'] ?? null,
                    'cgst' => $row['cgst'] ?? 0,
                    'sgst' => $row['sgst'] ?? 0,
                    'feature_image_id' => $row['feature_image_id'] ?? null,
                    'best_offers' => $row['best_offers'] ?? 0,
                    'top_selling' => $row['top_selling'] ?? 0,
                    'is_active' => $row['is_active'] ?? 1,
                    'is_deleted' => 0,
                ]);

                // Insert Variant (if present)
                if (!empty($row['variant_name'])) {
                    $actualPrice = (float) $row['variant_actual_price'];
                    $sellingPrice = (float) $row['variant_selling_price'];
                    $savePriceRs = $actualPrice - $sellingPrice;
                    $savePercent = ($savePriceRs / $actualPrice) * 100;

                    ProductVariant::create([
                        'product_id' => $product->id,
                        'variant_name' => $row['variant_name'],
                        'variant_actual_price' => $actualPrice,
                        'variant_selling_price' => $sellingPrice,
                        'variant_save_price_in_rs' => $savePriceRs,
                        'variant_save_price_in_percent' => $savePercent,
                        'stock' => $row['stock'] ?? 0,
                        'attributes' => $row['attributes'] ?? json_encode([]),
                    ]);
                }
            }

            DB::commit();
            return back()->with('success', 'Products imported successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }


}
