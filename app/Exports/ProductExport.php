<?php

namespace App\Exports;

use App\Product;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductExport implements WithHeadings , ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
   

    public function collection()
    {
        // return Product::all();
    }

    public function headings(): array
    {
        return [
            'school','product_name','product_code','size','gender','wholesale_price','price','max_order_qty','min_order_qty','inventory',
        ];
    }
}
