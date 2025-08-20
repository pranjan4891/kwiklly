<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\Auth;

class AttributeController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $title = 'Admin | Attributes';
        $attributes = Attribute::where('is_deleted', 0)->latest()->get();
        return view('admin.attribute.index', compact('admin', 'title', 'attributes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        Attribute::create([
            'name' => $request->name,
            'is_active' => $request->is_active,
            'is_deleted' => 0,
        ]);

        return redirect()->back()->with('success', 'Attribute added successfully.');
    }

    public function update(Request $request, $id)
    {
        $attribute = Attribute::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $attribute->update([
            'name' => $request->name,
            'is_active' => $request->is_active,
        ]);

        return redirect()->back()->with('success', 'Attribute updated successfully.');
    }

    public function delete($id)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Attribute deleted successfully.');
    }

    public function indexAttributeValues()
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $title = 'Admin | Attribute Values';

        $attributes = Attribute::where('is_deleted', 0)->latest()->get();
        $attributeValues = AttributeValue::where('is_deleted', 0)->with('attribute')->latest()->get();

        $groupedValues = $attributeValues->groupBy('attribute_id');

        return view('admin.attribute.values', compact('admin', 'title', 'attributes', 'attributeValues', 'groupedValues'));
    }


    public function storeAttributeValue(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        AttributeValue::create([
            'attribute_id' => $request->attribute_id,
            'value' => $request->value,
            'is_active' => $request->is_active,
            'is_deleted' => 0,
        ]);

        return redirect()->back()->with('success', 'Attribute value added successfully.');
    }

    public function updateAttributeValue(Request $request, $id)
    {
        $attributeValue = AttributeValue::findOrFail($id);

        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $attributeValue->update([
            'attribute_id' => $request->attribute_id,
            'value' => $request->value,
            'is_active' => $request->is_active,
        ]);

        return redirect()->back()->with('success', 'Attribute value updated successfully.');
    }

    public function softDeleteAttributeValue($id)
    {
        $attributeValue = AttributeValue::findOrFail($id);
        $attributeValue->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Attribute value deleted successfully.');
    }

}
