<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\User;
use App\Shop;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(User $s)
    {
        $this->view = 'user';
        $this->route = 'user';
        $this->viewName = 'User';
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::get();
            return Datatables::of($query)
            ->addColumn('action', function ($row) {
                // $btn = '<a href="'.route('user.edit', $row->id).'">Edit</a>&nbsp&nbsp&nbsp<a href="'.route('user.delete', $row->id).'">Delete</a>';  
                $btn = view('layouts.actionbtnpermission')->with(['id'=>$row->id,'route'=>'user'])->render();           
                return $btn;
            })
            ->addColumn('shop_id', function ($row) {
                if($row->shop_id)
                {
                    $btn = $row->shop->name;
                }
                else
                {
                    $btn = '';
                }
                return $btn;
            })
            
            ->addColumn('roles', function ($row) {
                if($row->role == 1)
                {
                    $btn = 'Superadmin';
                }elseif($row->role == 2)
                {
                    $btn = 'Admin';
                }elseif($row->role == 4)
                {
                    $btn = 'Manager';
                }else{
                    $btn = 'Sales';
                }
                return $btn;
            })
            ->addColumn('singlecheckbox', function ($row) {

                $schk = view('layouts.singlecheckbox')->with(['id' => $row->id , 'status'=>$row->status])->render();

                return $schk;

            })

            ->rawColumns(['action','singlecheckbox','roles'])
            ->make(true);
        } 

        return view('user.index');
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
            $data['shop'] = Shop::get();

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
        $param['show_password'] = $param['password'];
        $param['password'] = bcrypt($param['password']);


        $user=User::create($param);
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
        $data['data'] = User::where('id', $id)->first();
        $data['module'] = $this->viewName;
        $data['resourcePath'] = $this->view;
        $data['shop'] = Shop::get();
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

        if($request->role == 2 || $request->role == 4)

        {
            // $("#shop").hide();
            $data['shop_id'] = NULL;
        }

        if($request->password)
        {
            $data['show_password'] = $request->password;
            $data['password'] = bcrypt($request->password);
        }else{
            unset($data['password']);
        }


        
        $data = User::where('id', $id)->update($data);



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
