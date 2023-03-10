<?php

namespace App\Exports;

use App\Models\Sku;
use App\Models\Type;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StocksExport implements FromCollection,WithHeadings,WithColumnWidths,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings():array{
        return[
            'Name',
            'Category',
            'Price',
            'Current Stock',
            'Min Stock',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 43,
            'B' => 33,
            'C' => 12,
            'D' => 12,
            'E' => 12,
        ];
    }

    public function collection()
    {
        return Sku::all();
    }

    // export relationship data with looping
    public function map($sku) : array{
        return [
            $sku->item_name,
            $sku->item->type()->name,
            $sku->price,
            $sku->stock,
            $sku->min_stock
        ];
    }
}
