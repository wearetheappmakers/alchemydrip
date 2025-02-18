<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Size;
use DB;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(Size $s)
    {
        $this->view = 'size';
        $this->route = 'size';
        $this->viewName = 'Size';
    }

    public function index(Request $request)
     {
        if ($request->ajax()) {
            $query = Size::get();
            return Datatables::of($query)
            ->addColumn('action', function ($row) {
                // $btn = '<a href="'.route('user.edit', $row->id).'">Edit</a>&nbsp&nbsp&nbsp<a href="'.route('user.delete', $row->id).'">Delete</a>';  
                $btn = view('layouts.actionbtnpermission')->with(['id'=>$row->id,'route'=>'size'])->render();           
                return $btn;
            })
            ->addColumn('size_type', function ($row) {
                if($row->size_type == 1)
                {
                    $btn = 'Size';
                }elseif($row->size_type == 2)
                {
                    $btn = 'Weight';
                }elseif($row->size_type == 3)
                {
                    $btn = 'Capacity';
                }else{
                    $btn = 'Number';
                }
                return $btn;
            })
            
            ->addColumn('singlecheckbox', function ($row) {

                    $schk = view('layouts.singlecheckbox')->with(['id' => $row->id , 'status'=>$row->status])->render();

                    return $schk;

                })
         
            ->rawColumns(['action','singlecheckbox'])
            ->make(true);
        } 

        return view('size.index');
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
        $param = $request->all();
        // $param['show_password'] = $param['password'];
        // $param['password'] = bcrypt($param['password']);
       

        $user=Size::create($param);
        if($user)
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
        $data['data'] = Size::where('id', $id)->first();
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
        $data = $request->all();
        unset($data['_token'],$data['_method']);
        if($request->password)
        {
            $data['show_password'] = $request->password;
            $data['password'] = bcrypt($request->password);
        }else{
            unset($data['password']);
        }
        $data = Size::where('id', $id)->update($data);
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

}
