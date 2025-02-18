<?php

namespace App\Exports;

use App\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductDataExport implements FromCollection, WithHeadings , ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings(): array
    {
        return [
            'school','product_name','product_code','size','gender','wholesale_price','price','max_order_qty','min_order_qty','inventory',
        ];
    }

    public function collection()
    {
        $product =  Product::with('school','sizes','genders','product_inventorys','product_prices')->get();
        $data = [];
        foreach ($product as $key => $value) {
            $value->school_id = $value->school->name;
            $value->name = $value->name;
            $value->code = $value->code;
            $sizedata = [];
            foreach ($value->sizes as $sizevalue) {
                $sizedata[] = $sizevalue->name;
            }
            $value->size = $sizedata ? implode(',', $sizedata) : 'null';
            $zendata = [];
            foreach ($value->genders as $zenvalue) {
                $zendata[] = $zenvalue->name;
            }
            $value->gender = $zendata ? implode(',', $zendata) : 'null';
            
            $allpricedata = [];
            foreach ($value->product_prices as $wholvalue) {
                $allpricedata[] = $wholvalue->wholesale_price;
            }
            $value->wholesale_price = $allpricedata ? implode(',', $allpricedata) : 'null';

            $pricedata = [];
            foreach ($value->product_prices as $privalue) {
                $pricedata[] = $privalue->price;
            }
            $value->price = $pricedata ? implode(',', $pricedata) : 'null';
            
            $maxqtydata = [];
            foreach ($value->product_inventorys as $maxvalue) {
                $maxqtydata[] = $maxvalue->max_order_qty;
            }
            $value->max_order_qty = $maxqtydata ? implode(',', $maxqtydata) : 'null';
            
            $minqtydata = [];
            foreach ($value->product_inventorys as $minvalue) {
                $minqtydata[] = $minvalue->min_order_qty;
            }
            $value->min_order_qty = $minqtydata ? implode(',', $minqtydata) : 'null';
            
            $qtydata = [];
            foreach ($value->product_inventorys as $qtyvalue) {
                $qtydata[] = $qtyvalue->inventory;
            }
            $value->inventory = $qtydata ? implode(',', $qtydata) : 'null';
            
            
            unset($value->id,$value->status,$value->created_at,$value->updated_at);

        }
        return $product;
    }

    public function query()
    {
        $product =  Product::select('id','school_id','code','name')->with('school','sizes','genders','product_inventorys','product_prices')->get();
        $data = [];
        
        foreach ($product as $value) {

            $value['school_id'] = $value->school->name;

            $data['product_code'] = $value->code;
            $data['product_name'] = $value->name;

            $data['school'] = $value->school->name;
            $sizedata = [];
            foreach ($value->sizes as $sizevalue) {
                $sizedata[] = $sizevalue->name;
            }
            $data['size'] = $sizedata ? implode(',', $sizedata) : 0;
            $zendata = [];

            foreach ($value->genders as $zenvalue) {
                $zendata[] = $zenvalue->name;
            }
            $data['gender'] = $zendata ? implode(',', $zendata) : 0;

            $minqtydata = [];

            foreach ($value->product_inventorys as $minvalue) {
                $minqtydata[] = $minvalue->min_order_qty;
            }

            $data['min_order_qty'] = $minqtydata ? implode(',', $minqtydata) : 0;
            $maxqtydata = [];
            foreach ($value->product_inventorys as $maxvalue) {
                $maxqtydata[] = $maxvalue->max_order_qty;
            }
            $data['max_order_qty'] = $maxqtydata ? implode(',', $maxqtydata) : 0;
            $qtydata = [];
            foreach ($value->product_inventorys as $qtyvalue) {
                $qtydata[] = $qtyvalue->inventory;
            }
            $data['inventory'] = $qtydata ? implode(',', $qtydata) : 0;
            $pricedata = [];
            foreach ($value->product_prices as $privalue) {
                $pricedata[] = $privalue->price;
            }
            $data['price'] = $pricedata ? implode(',', $pricedata) : 0;
            $allpricedata = [];
            foreach ($value->product_prices as $wholvalue) {
                $allpricedata[] = $wholvalue->wholesale_price;
            }
            $data['wholesale_price'] = $allpricedata ? implode(',', $allpricedata) : 0;

        }
        // dd($data);
        return $data;
    }

    public function map($data): array
    {
        return [
            $data->product_code,
            $data->product_name,
            $data->school,
            $data->size,
            $data->gender,
            $data->min_order_qty,
            $data->max_order_qty,
            $data->inventory,
            $data->price,
            $data->wholesale_price,
        ];
    }
}
