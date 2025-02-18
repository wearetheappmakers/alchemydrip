<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\So;
use App\School;
use App\Product;
use App\So_child;
use App\So_other;
use App\Size;
use App\Gender;
use App\ProductInventory;
use App\ProductPrice;
use App\Otherproduct;
use DataTables;

class SoController extends Controller
{

    public function __construct(So $s)
    {
        $this->view = 'so';
        $this->route = 'so';
        $this->viewName = 'Sales Order';
    }

    public function index(Request $request)
    {
        $query = So::get();
        if($request->status_id == 1)
        {
            $query = So::where('status',$request->status_id)->get();
        }elseif($request->status_id == 2)
        {
            $query = So::where('status',0)->get();
        }
        if ($request->ajax()) {
            return Datatables::of($query)
            ->addColumn('action', function ($row) {
                $btn = view('layouts.actionbtnpermission')->with(['id'=>$row->id,'route'=>'so'])->render();           
                return $btn;
            })
            ->addColumn('singlecheckbox', function ($row) {

                $schk = view('layouts.singlecheckbox')->with(['id' => $row->id , 'status'=>$row->status])->render();

                return $schk;

            })
            ->addColumn('shops', function ($row) {
                $schk = $row->users->shop->name;
                return $schk;
            })
            ->addColumn('usersname', function ($row) {
                $schk = $row->users->name;
                return $schk;
            })
            ->rawColumns(['action', 'singlecheckbox','shops','usersname'])
            ->make(true);
        }
        $data['status_id'] = '';
        if($request->status_id)
        {
            $data['status_id'] = $request->status_id;
        }
        return view('so.index')->with($data);
    }
    public function create()
    {
        {
            $data['title'] = 'Add ' . $this->viewName;
            $data['module'] = $this->viewName;
            $data['resourcePath'] = $this->view;
            $data['school'] = School::get();
            $data['product'] = Product::get();
            $data['size'] = ProductInventory::get();
            $data['gender'] = ProductInventory::get();
            $data['name'] = Otherproduct::get();
            $data['price'] = Otherproduct::get();


            return view('general.add_form')->with($data);
        }
    }
    public function store(Request $request)
    {
        $so = new So();

        $so->name = $request->name;
        $so->number = $request->number;
        $so->address = $request->address;
        $so->total_amount = $request->total_amount;
        $so->total_qty = $request->total_qty;
        $so->other_total_qty = $request->other_total_qty;
        $so->other_total_amount = $request->other_total_amount;

        $total_qty = (integer)$request->total_qty;
        $other_total_qty = (integer)$request->other_total_qty;
        $total_amount = (integer)$request->total_amount;
        $other_total_amount = (integer)$request->other_total_amount;

        $so->grand_total_qty = $total_qty + $other_total_qty;
        $so->grand_total_amount = $total_amount + $other_total_amount;
        $so->save();

        if(!empty($request->product_id))
        {
            foreach ($request->product_id as $key => $value)
            {
                $html = ProductInventory::where('id',$request->size_id[$key])->first();
                $so_child = new So_child();
                $so_child->so_id = $so->id;
                $so_child->product_id = $request->product_id[$key];
                $so_child->school_id = $request->school_id[$key];
                $so_child->qty = $request->qty[$key];
                $so_child->price = $request->price[$key];
                $so_child->wholesale_price = $request->w_price[$key];
                $so_child->amount = $request->amount[$key];

                $so_child->size_id = $html->size_id;

                $so_child->gender_id = $html->gender_id;

                $so_child->save();

                $html->used = $html->used + $request->qty[$key];
                $html->save();
            }
        }
        if($request->other_name)
        {
            foreach ($request->other_name as $key => $value)
            {
                if($value)
                {

                    $so_other = new So_other();
                    $so_other->so_id = $so->id;
                    $so_other->other_product_id = $value;
                    $so_other->name = $request->other_name[$key];
                    $so_other->price = $request->other_price[$key];
                    $so_other->qty = $request->other_qty[$key];
                    $so_other->amount = $request->other_amount[$key];
                    // dd($so_other);

                    $so_other->save();
                }
            }
        }   
        if($so)
        {
            return response()->json(['status' => 'success']);

        }else
        {
            return response()->json(['status' => 'error']);
        }
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $data['title'] = 'Edit ' . $this->viewName;
        $data['data'] = So::where('id', $id)->first();
        $data['edit_so_clone'] = So_child::where('so_id',$id)->get();
        $data['edit_so_other_clone'] = So_other::where('so_id',$id)->get();
        $data['module'] = $this->viewName;
        $data['resourcePath'] = $this->view;
        $data['school'] = School::get();
        $data['sizes'] = Size::get();
        $data['product'] = Product::get();
        $data['inventory'] = ProductInventory::with('sizes','genders')->get();
        $data['name'] = Otherproduct::get();
        $data['price'] = Otherproduct::get();
        return view('general.edit_form')->with($data);
    }
    public function update(Request $request, $id)
    {
        $data = So::where('id', $id)->first();
        unset($data['_token'],$data['_method']);
        $data->name = $request->name;
        $data->number = $request->number;
        $data->address = $request->address;
        $data->total_amount = $request->total_amount;
        $data->total_qty = $request->total_qty;
        $data->other_total_qty = $request->other_total_qty;
        $data->other_total_amount= $request->other_total_amount;
        
        $total_qty = (integer)$request->total_qty;
        $other_total_qty = (integer)$request->other_total_qty;
        $total_amount = (integer)$request->total_amount;
        $other_total_amount = (integer)$request->other_total_amount;

        $data->grand_total_qty = $total_qty + $other_total_qty;
        $data->grand_total_amount = $total_amount + $other_total_amount;
        $data->save();

        if($request->product_id)
        {
            foreach ($request->product_id as $key => $value)
            {
                if($value != '')
                {
                    $html = ProductInventory::where('id',$request->size_id[$key])->first();
                    $so_child = new So_child();
                    $so_child->so_id = $id;
                    $so_child->product_id = $value;
                    $so_child->school_id = $request['school_id'][$key];
                    $so_child->qty = $request['qty'][$key];
                    $so_child->price = $request['price'][$key];
                    $so_child->wholesale_price = $request['w_price'][$key];
                    $so_child->amount = $request['amount'][$key];

                    $so_child->size_id = $html['size_id'];
                    $so_child->gender_id = $html['gender_id'];

                    $so_child->save();

                    $html->used = $html->used + $request->qty[$key];
                    $html->save();

                }
            }
        }
        

        if($request->edit_product_id)
        {
            foreach ($request->edit_product_id as $key => $value) 
            {

                if($value != '')
                {

                    $html = ProductInventory::where('id',$request->edit_inventory_id[$key])->first();

                    $edit_so_child['so_id'] = $id;
                    $edit_so_child['product_id'] = $value;
                    $edit_so_child['school_id'] = $request['edit_school_id'][$key];
                    $edit_so_child['qty'] = $request['edit_qty'][$key];
                    $edit_so_child['price'] = $request['edit_price'][$key];
                    $edit_so_child['wholesale_price'] = $request['edit_w_price'][$key];
                    $edit_so_child['amount'] = $request['edit_amount'][$key];

                    $edit_so_child['size_id'] = $html['size_id'];
                    $edit_so_child['gender_id'] = $html['gender_id'];


                    $edit_so_child_update = So_child::where('id',$request['so_child_id'][$key])->first();
                    $num = (integer) $edit_so_child['qty'];
                    $html->used = $html->used + $num - $edit_so_child_update->qty;
                    $html->save();
                     
                    $edit_so_child_update->update($edit_so_child);
                    // dd($edit_so_child_update);
                    $edit_so_child_update->save();
                }    
            }
        }
        if($request->other_name)
        {
            foreach ($request->other_name as $key => $value)
            {
                if($value != '')
                {
                    $so_other = new So_other();
                    $so_other->so_id = $id;
                    $so_other->other_product_id = $value;
                     // dd($so_other);    
                    $so_other->name = $request['other_name'][$key];
                    $so_other->qty = $request['other_qty'][$key];
                    $so_other->price = $request['other_price'][$key];
                    $so_other->amount = $request['other_amount'][$key];
                    $so_other->save();


                }
            }
        }
        

        if($request->edit_other_name)
        {
            foreach ($request->edit_other_name as $key => $value) 
            {

                if($value != '')
                {

                    $edit_so_other['so_id'] = $id;
                    $edit_so_other['other_product_id'] = $value;
                    $edit_so_other['name'] = $request['edit_other_name'][$key];
                    $edit_so_other['qty'] = $request['edit_other_qty'][$key];
                    $edit_so_other['price'] = $request['edit_other_price'][$key];
                    $edit_so_other['amount'] = $request['edit_other_amount'][$key];

                    $edit_so_other_update = So_other::where('id',$request['so_other_id'][$key])->update($edit_so_other);

                }       
            }
        }

        if($data)
        {
            return response()->json(['status' => 'success']);
        }else
        {
            return response()->json(['status' => 'error']);

        }
    }
    public function delete($id)
    {
        $data = So_child::where('id',$id)->first();
        $update = ProductInventory::where('product_id',$data->product_id)->where('size_id',$data->size_id)->where('gender_id',$data->gender_id)->first();
        $update->used = $update->used + $data->qty;
        $update->save();
        $data->delete();
        
        if($data)
        {
            return response()->json(['status'=>'success']);
        }
        else
        {
            return response()->json(['status'=>'error']);
        }
    }
    public function deleteclone($id)
    {
        $data = So_other::where('id',$id)->delete();
        if($data)
        {
            return response()->json(['status'=>'success']);
        }
        else
        {
            return response()->json(['status'=>'error']);
        }
    }

    public function getproduct(Request $request)
    {
        $data = Product::where('school_id',$request->school_id)->get();
        $html = "<option value=''>Select</option>";
        foreach ($data as $key => $value) {
            $html .= "<option value=".$value->id.">".$value->name."</option>";
        }

        echo $html;
    }


    public function getsize(Request $request)
    {
        $data = ProductInventory::with('sizes','genders')->where('product_id',$request->product_id)->get();
        $html = "<option value=''>Select</option>";
        foreach ($data as $key => $value) {
            if($value->inventory-$value->used > 0)
            {
                $html .= "<option value=".$value->id.">".$value->sizes->name.' - '.$value->genders->name."</option>";
            }
        }

        echo $html;
    }

    public function getprice(Request $request)
    {
        $html = ProductInventory::where('id',$request->product_id)->first();
        $inventory = ProductPrice::where('product_id',$html->product_id)->where('size_id',$html->size_id)->where('gender_id',$html->gender_id)->select('price','wholesale_price')->first();  

        return response()->json(['data'=>$inventory]);

    }
    public function getotherprice(Request $request)
    {
       $html = Otherproduct::where('id',$request->other_name)->value('price');
        return response()->json(['data'=>$html]);

    }
}
