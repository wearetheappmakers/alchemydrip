<?php

namespace App\Imports;

use App\Product;
use App\School;
use App\Size;
use App\ProductSize;
use App\Gender;
use App\ProductGender;
use App\ProductPrice;
use App\ProductInventory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel , WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        if(isset($row['school']) ){
            $school = School::where('name',$row['school'])->first();
            if($school){
                $schoolId = $school->id;
            } else {
                $newschool = School::create([
                    'name' => $row['school'],
                ]);
                $schoolId = $newschool->id;
            }
            $productdata = Product::where('code',$row['product_code'])->first();
            if($productdata){
                $productdata->update([
                    'school_id' => $schoolId,
                    'name' => $row['product_name'],
                    'code' => $row['product_code'],
                ]);
                $product = $productdata;
            } else {
                $product = Product::create([
                    'school_id' => $schoolId,
                    'name' => $row['product_name'],
                    'code' => $row['product_code'],
                ]);
            }
            
            $size = explode(',',$row['size']);
            $productsizedelete = ProductSize::where('product_id',$product->id)->delete();
            foreach($size as $value){
                $sizedata = Size::where('name',$value)->first();
                // $productsizedelete->delete();
                if($sizedata){
                    ProductSize::create([
                        'product_id' => $product->id,
                        'size_id' => $sizedata->id,
                    ]);
                } else {
                    $string = str_random(15);
                    $sizedata = Size::create([
                        'name' => $value,
                        'code' => $string,
                        'status' => 1,
                    ]);
                    ProductSize::create([
                        'product_id' => $product->id,
                        'size_id' => $sizedata->id,
                    ]);
                }
            }

            //
            $gender = explode(',',$row['gender']);
            $productgendelete = ProductGender::where('product_id',$product->id)->delete();

            foreach($gender as $value){
                $gendata = Gender::where('name',$value)->first();
                // $productgendelete->delete();
                if($gendata){
                    ProductGender::create([
                        'product_id' => $product->id,
                        'gender_id' => $gendata->id,
                    ]);
                } else {
                    $gendata = Gender::create([
                        'name' => $value,
                        'status' => 1,
                    ]);
                    ProductGender::create([
                        'product_id' => $product->id,
                        'gender_id' => $gendata->id,
                    ]);
                }
            }
            $productPrice = explode(',',$row['price']);
            $productwholePrice = explode(',',$row['wholesale_price']);
            $productmaxqty = explode(',',$row['max_order_qty']);
            $productminqty = explode(',',$row['min_order_qty']);
            $productinventory = explode(',',$row['inventory']);
            $size = explode(',',$row['size']);
            $productpricedelete = ProductPrice::where('product_id',$product->id)->delete();
            // $productpricedelete->delete();
            $productinvendelete = ProductInventory::where('product_id',$product->id)->delete();
            // $productinvendelete->delete();
            foreach($size as $key => $value){
                
                ProductPrice::create([
                    'product_id' => $product->id,
                    'gender_id' => $gendata->id,
                    'size_id' => $sizedata->id,
                    'price' => isset($productPrice[$key]) ? $productPrice[$key] : $productPrice[0],
                    'wholesale_price' => isset($productwholePrice[$key]) ? $productwholePrice[$key] :  $productwholePrice[0],
                ]);

                ProductInventory::create([
                    'product_id' => $product->id,
                    'gender_id' => $gendata->id,
                    'size_id' => $sizedata->id,
                    'min_order_qty' => isset($productminqty[$key]) ? $productminqty[$key] : $productminqty[0],
                    'max_order_qty' => isset($productmaxqty[$key]) ? $productmaxqty[$key] : $productmaxqty[0],
                    'inventory' => isset($productinventory[$key]) ? $productinventory[$key] : $productinventory[0],
                ]);
            }
            return $product;
        }
    }
}
