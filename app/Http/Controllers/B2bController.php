<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\B2b;
use App\B2b_child;

class B2bController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(B2b $s)
    {
        $this->view = 'b2b';
        $this->route = 'b2b';
        $this->viewName = 'B2B';
    }
    public function index(Request $request)
    {
        $query = B2b::with('users','users.shop')->get();
        if($request->status_id == 1)
        {
            $query = B2b::with('users','users.shop')->where('status',$request->status_id)->get();
        }elseif($request->status_id == 2)
        {
            $query = B2b::with('users','users.shop')->where('status',0)->get();
        }
        if ($request->ajax()) {
            return Datatables::of($query)
            ->addColumn('action', function ($row) {
                // $btn = '<a href="'.route('user.edit', $row->id).'">Edit</a>&nbsp&nbsp&nbsp<a href="'.route('user.delete', $row->id).'">Delete</a>';  
                $btn = view('layouts.actionbtnpermission')->with(['id'=>$row->id,'route'=>'b2b'])->render();           
                return $btn;
            })


            ->addColumn('singlecheckbox', function ($row) {
                $schk = view('layouts.singlecheckbox')->with(['id' => $row->id , 'status'=>$row->status])->render();
                return $schk;
            })
            ->addColumn('shops', function ($row) {
                if($row->users->shop)
                {
                    $schk = $row->users->shop->name;
                }else{
                    $schk = 'Admin';
                }
                return $schk;
            })
            ->addColumn('usersname', function ($row) {
                $schk = $row->users->name;
                return $schk;
            })
            ->rawColumns(['action','singlecheckbox','shops','usersname'])
            ->make(true);
        } 
        $data['status_id'] = '';
        if($request->status_id)
        {
            $data['status_id'] = $request->status_id;
        }

        return view('b2b.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = 'Add ' . $this->viewName;
        $data['module'] = $this->viewName;
        $data['resourcePath'] = $this->view;

        return view('general.add_form')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $b2b = new B2b();

        $b2b->name = $request->name;
        $b2b->number = $request->number;
        $b2b->email = $request->email;
        $b2b->address = $request->address;
        $b2b->address2 = $request->address2;
        $b2b->address3 = $request->address3;
        $b2b->gstin = $request->gstin;
        $b2b->pan_no = $request->pan_no;
        $b2b->total_amount = $request->total_amount;
        $b2b->total_qty = $request->total_qty;

        $b2b->save();


        if(!empty($request->name))

        {

            foreach ($request->names as $key => $value)
            {

                $b2b_child = new B2b_child();
                $b2b_child->b2b_id = $b2b->id;
                $b2b_child->name = $request->names[$key];
                $b2b_child->qty = $request->qty[$key];
                $b2b_child->price = $request->price[$key];
                $b2b_child->amount = $request->amount[$key];

                $b2b_child->save();
            }

            if($b2b)
            {
                return response()->json(['status' => 'success']);
            }else
            {
                return response()->json(['status' => 'error']);
            }
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
        //
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
        $data['data'] = B2b::where('id', $id)->first();
        $data['edit_b2b_clone'] = B2b_child::where('b2b_id',$id)->get();
        $data['module'] = $this->viewName;
        $data['resourcePath'] = $this->view;
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
        $data = B2b::where('id', $id)->first();
        unset($data['_token'],$data['_method']);
        
        $data->name = $request->name;
        $data->number = $request->number;
        $data->email = $request->email;
        $data->address = $request->address;
        $data->address2 = $request->address2;
        $data->address3 = $request->address3;
        $data->gstin = $request->gstin;
        $data->pan_no = $request->pan_no;
        $data->total_amount = $request->total_amount;
        $data->total_qty = $request->total_qty;
        $data->total = $request->total;
        $data->save();

        foreach ($request->names as $key => $value)
        {
            if($value != '')
            {
                $b2b_child = new B2b_child();
                $b2b_child->b2b_id = $id;
                $b2b_child->name = $request['names'][$key];
                $b2b_child->qty = $request['qty'][$key];
                $b2b_child->price = $request['price'][$key];
                $b2b_child->amount = $request['amount'][$key];
                $b2b_child->gst = $request['gst'][$key];
                $b2b_child->gst_amount = $request['gst_amount'][$key];
                $b2b_child->total = $request['total'][$key];
                $b2b_child->save();
                


            }
        }

        if($request->edit_names)
        {
            foreach ($request->edit_names as $key => $value) 
            {

             if($value != '')
             {

                $edit_b2b_child['b2b_id'] = $id;
                $edit_b2b_child['name'] = $request['edit_names'][$key];
                $edit_b2b_child['qty'] = $request['edit_qty'][$key];
                $edit_b2b_child['price'] = $request['edit_price'][$key];
                $edit_b2b_child['amount'] = $request['edit_amount'][$key];
                $edit_b2b_child['gst'] = $request['edit_gst'][$key];
                $edit_b2b_child['gst_amount'] = $request['edit_gst_amount'][$key];
                $edit_b2b_child['total'] = $request['edit_total'][$key];

                $edit_b2b_update = B2b_child::where('id',$request['b2b_child_id'][$key])->update($edit_b2b_child);



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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $data = B2b_child::where('id',$id)->delete();
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

    public function pdf(Request $request)
    {
        return view('general.invoice');
    }
}
