<?php

namespace App\Exports;

use App\Models\Subcategory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubcategoriesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Subcategory::with('category', 'attributes')
            ->where('is_deleted', 0)
            ->get()
            ->map(function ($subcat) {
                return [
                    'ID' => $subcat->id,
                    'Category' => $subcat->category->name ?? '',
                    'Subcategory' => $subcat->sub_cat_name,
                    'Slug' => $subcat->sub_cat_slug,
                    'Status' => $subcat->is_active ? 'Active' : 'Inactive',
                    'Attributes' => $subcat->attributes->pluck('name')->implode(', '),
                ];
            });
    }

    public function headings(): array
    {
        return ['ID', 'Category', 'Subcategory', 'Slug', 'Status', 'Attributes'];
    }
}

