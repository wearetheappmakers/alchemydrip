<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;

use Illuminate\Http\Response;

use App\ProductInventory;

use DataTables;

use DB;



class InventoryController extends Controller

{

    

    public function index(Request $request)

    {
        $data['get_data'] = DB::table('product_inventory as pi')

        ->leftJoin('gender as gg','gg.id','pi.gender_id')

        ->leftJoin('size as ss','ss.id','pi.size_id')

        ->leftJoin('product as pp','pp.id','pi.product_id')

        ->leftJoin('school as sc','sc.id','pp.school_id')

        ->select('pi.*','gg.name as gender_name','ss.name as size_name','pp.name as product_name','sc.name as schoolname')

        ->get();

        
        foreach ($data['get_data'] as $key => $value) {
            
            $value->remaining = $value->inventory - $value->used;
        }
        return view('inventory.index')->with($data);

    }

    public function indexminimum()
    {
        $data['get_data'] = DB::table('product_inventory as pi')

        ->leftJoin('gender as gg','gg.id','pi.gender_id')

        ->leftJoin('size as ss','ss.id','pi.size_id')

        ->leftJoin('product as pp','pp.id','pi.product_id')

        ->leftJoin('school as sc','sc.id','pp.school_id')

        ->select('pi.*','gg.name as gender_name','ss.name as size_name','pp.name as product_name','sc.name as schoolname')

        ->get();

        
        foreach ($data['get_data'] as $key => $value) {
            
            $value->remaining = $value->inventory - $value->used;
            if($value->remaining >= $value->min_order_qty)
            {
                unset($data['get_data'][$key]);
            }
        }
        return view('inventory.minimum')->with($data);
    }

    public function update(Request $request)

    {

        $update_inventory = $request->update_inventory;

        foreach ($update_inventory as $key => $value) {

            $inventory = DB::table('product_inventory')


            ->where('id',$key)

            ->value('inventory');

            $inventory = $value + $inventory;

            $updated = DB::table('product_inventory')

            ->where('id',$key)

            ->update(['inventory'=>$inventory]);      

            //  $size = DB::table('size')
           

            // ->where('id',$key)

            // ->value('size_id');

            // $size = $value + $size;

            // $updated = DB::table('size')

            // ->where('id',$key)

            // ->update(['inventory'=>$inventory]);             

        }



        

          return response()->json(['status'=>'success']);

        

    }

}

