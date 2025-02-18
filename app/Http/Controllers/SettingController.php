<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;

class SettingController extends Controller
{
    public function __construct(Setting $s)
    {
        $this->view = 'setting';
        $this->route = 'setting';
        $this->viewName = 'Setting';
    }

    public function edit($id)
    {

        $data['title'] = 'Edit ' . $this->viewName;
        $data['module'] = $this->viewName;
        $data['resourcePath'] = $this->view;

        $data['edit'] = Setting::where('id', $id)->first();
        return view('setting.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token'],$data['_method']);
        if($request->hasFile('logo'))
        {
            $doc1 = time().'-'.request()->logo->getClientOriginalName();
            request()->logo->move(public_path('setting/'), $doc1);
            $data['logo'] = $doc1;
        }
        $setting = Setting::where('id', $id)->update($data);
        if ($setting)
        {
            return response()->json(['status'=>'success']);
        }else{
            return response()->json(['status'=>'error']);
        }
    }
}
