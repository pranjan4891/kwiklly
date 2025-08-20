<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoriesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Category::where('is_deleted', 0)
            ->select('id', 'name', 'slug', 'is_active')
            ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Category Name', 'Slug', 'Status'];
    }
}
