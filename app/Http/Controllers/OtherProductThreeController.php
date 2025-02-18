<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\OtherProductThree;

class OtherProductThreeController extends Controller
{
     public function __construct(OtherProductThree $s)
    {
        $this->view = 'otherproductthree';
        $this->route = 'otherproductthree';
        $this->viewName = 'OtherProductThree';
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = OtherProductThree::get();
            return Datatables::of($query)
            ->addColumn('action', function ($row) {
                $btn = view('layouts.actionbtnpermission')->with(['id'=>$row->id,'route'=>'otherproductthree'])->render();           
                return $btn;
            })

            ->addColumn('singlecheckbox', function ($row) {

                    $schk = view('layouts.singlecheckbox')->with(['id' => $row->id , 'status'=>$row->status])->render();

                    return $schk;

                })
         
            ->rawColumns(['action','singlecheckbox'])
            ->make(true);
        } 

        return view('otherproductthree.index');
    }

    public function create()
    {
        {
        $data['title'] = 'Add Accessory-3';
        $data['module'] = $this->viewName;
        $data['resourcePath'] = $this->view;

        return view('general.add_form')->with($data);
        }
    }

    public function store(Request $request)
    {
        $param = $request->all();
        $otherproduct=OtherProductThree::create($param);
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
        $data['title'] = 'Edit Accessory-3';
        $data['data'] = OtherProductThree::where('id', $id)->first();
        $data['module'] = $this->viewName;
        $data['resourcePath'] = $this->view;
        return view('general.edit_form')->with($data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token'],$data['_method']);
       
        $data = OtherProductThree::where('id', $id)->update($data);
        if($data)
        {
            return response()->json(['status' => 'success']);
        }else
        {
            return response()->json(['status' => 'error']);

        }
    }    
}