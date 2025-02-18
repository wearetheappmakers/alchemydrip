<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\OtherProductTwo;

class OtherProductTwoController extends Controller
{
     public function __construct(OtherProductTwo $s)
    {
        $this->view = 'otherproducttwo';
        $this->route = 'otherproducttwo';
        $this->viewName = 'OtherProductTwo';
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = OtherProductTwo::get();
            return Datatables::of($query)
            ->addColumn('action', function ($row) {
                $btn = view('layouts.actionbtnpermission')->with(['id'=>$row->id,'route'=>'otherproducttwo'])->render();           
                return $btn;
            })

            ->addColumn('singlecheckbox', function ($row) {

                    $schk = view('layouts.singlecheckbox')->with(['id' => $row->id , 'status'=>$row->status])->render();

                    return $schk;

                })
         
            ->rawColumns(['action','singlecheckbox'])
            ->make(true);
        } 

        return view('otherproducttwo.index');
    }

    public function create()
    {
        {
        $data['title'] = 'Add Accessory-2';
        $data['module'] = $this->viewName;
        $data['resourcePath'] = $this->view;

        return view('general.add_form')->with($data);
        }
    }

    public function store(Request $request)
    {
        $param = $request->all();
        $otherproduct=OtherProductTwo::create($param);
        if($otherproduct)
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
        $data['title'] = 'Edit Accessory-2';
        $data['data'] = OtherProductTwo::where('id', $id)->first();
        $data['module'] = $this->viewName;
        $data['resourcePath'] = $this->view;
        return view('general.edit_form')->with($data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token'],$data['_method']);
       
        $data = OtherProductTwo::where('id', $id)->update($data);
        if($data)
        {
            return response()->json(['status' => 'success']);
        }else
        {
            return response()->json(['status' => 'error']);

        }
    }    
}