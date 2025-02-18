<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Product;
use App\Po;
use App\Po_child;
use App\Inventory;

class PoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(Po $s)
    {
        $this->view = 'po';
        $this->route = 'po';
        $this->viewName = 'Po';
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Po::get();
            return Datatables::of($query)
            ->addColumn('action', function ($row) {
                // $btn = '<a href="'.route('user.edit', $row->id).'">Edit</a>&nbsp&nbsp&nbsp<a href="'.route('user.delete', $row->id).'">Delete</a>';  
                $btn = view('layouts.actionbtnpermission')->with(['id'=>$row->id,'route'=>'po'])->render();           
                return $btn;
            })

            // ->addColumn('singlecheckbox', function ($row) {

            //         $schk = view('layouts.singlecheckbox')->with(['id' => $row->id , 'status'=>$row->status])->render();

            //         return $schk;

            //     })

            ->rawColumns(['action'])
            ->make(true);
        } 

        return view('po.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        {
            $data['title'] = 'Add ' . $this->viewName;
            $data['module'] = $this->viewName;
            $data['resourcePath'] = $this->view;
            $data['product'] = Product::get();

            return view('general.add_form')->with($data);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     $po = new Po();

     $po->supplier_name = $request->supplier_name;
     $po->supplier_number = $request->supplier_number;
     $po->total_amount = $request->total_amount;
     $po->total_qty = $request->total_qty;
     $po->save();

     if(!empty($request->product_id))

     {

        foreach ($request->product_id as $key => $value)
        {
            $po_child = new Po_child();
            $po_child->po_id = $po->id;
            $po_child->product_id = $request->product_id[$key];
            $po_child->qty = $request->qty[$key];
            $po_child->price = $request->price[$key];
            $po_child->save();

            // $inv['po_child_id'] = $po_child->id;
            // $inv['inventory'] = $request->qty[$key];
            // $inv['product_id'] = $value;
            // $inventory = Inventory::create($inv);
        }
    }

    if($po)
    {
        return response()->json(['status' => 'success']);
    }else
    {
        return response()->json(['status' => 'error']);
    }
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['title'] = 'Edit ' . $this->viewName;
        $data['data'] = Po::where('id', $id)->first();
        $data['edit_details_clone'] = Po_child::where('po_id',$id)->get();
        $data['module'] = $this->viewName;
        $data['resourcePath'] = $this->view;
        $data['product'] = Product::get();

        return view('general.edit_form')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = Po::where('id', $id)->first();
        unset($data['_token'],$data['_method']);
        // if($request->password)
        // {
        //     $data['show_password'] = $request->password;
        //     $data['password'] = bcrypt($request->password);
        // }else{
        //     unset($data['password']);
        // }
        $data->supplier_name = $request->supplier_name;
        $data->supplier_number = $request->supplier_number;
        $data->total_amount = $request->total_amount;
        $data->total_qty = $request->total_qty;
        $data->save();

        foreach ($request->product_id as $key => $value)
        {
            if($value != '')
            {
                $po_child = new Po_child();
                $po_child->po_id = $data->id;
                $po_child->product_id = $request->product_id[$key];
                $po_child->qty = $request->qty[$key];
                $po_child->price = $request->price[$key];
                $po_child->save();

                // $inv['po_child_id'] = $po_child->id;
                // $inv['inventory'] = $request->qty[$key];
                // $inv['product_id'] = $value;
                // $inventory = Inventory::create($inv);
            }
        }

        if($request->edit_product_id)
        {
            foreach ($request->edit_product_id as $key => $value) 
            {
                if($value != '')
                {
                    $edit_po_child['po_id'] = $id;
                    $edit_po_child['product_id'] = $value;
                    $edit_po_child['qty'] = $request->edit_qty[$key];
                    $edit_po_child['price'] = $request->edit_price[$key];
                    $edit_po_child_update = Po_child::where('id',$request['po_child_id'][$key])->update($edit_po_child);

                    // $inv['po_child_id'] = $request['po_child_id'][$key];
                    // $inv['inventory'] = $request->edit_qty[$key];
                    // $inv['product_id'] = $value;
                    // $inventory = Inventory::where('po_child_id',$request['po_child_id'][$key])->update($inv);
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

    public function delete_clone($id)
    {
        $data = Po_child::where('id',$id)->delete();
        // dd($data);
        if($data)
        {
            return response()->json(['status'=>'success']);
        }
        else
        {
            return response()->json(['status'=>'error']);
        }
    }
}
