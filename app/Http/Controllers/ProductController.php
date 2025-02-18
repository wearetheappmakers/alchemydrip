<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Product;
use App\School;
use App\Size;
use App\Gender;
use App\ProductSize;
use App\ProductGender;
use App\ProductInventory;
use App\ProductPrice;
use App\Ledger;
use App\Shop;
use App\So;
use App\So_child;
use App\So_other;
use App\So_OtherTwo;
use App\SoRaw;
use DB;
use Excel;
use App\Imports\ProductImport;
use App\Exports\ProductExport;
use App\Exports\ProductDataExport;
use Carbon\Carbon;
use PDF;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(Product $s)
    {
        $this->view = 'product';
        $this->route = 'product';
        $this->viewName = 'Product';
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Product::get();
            return Datatables::of($query)
            ->addColumn('action', function ($row) {
                // $btn = '<a href="'.route('user.edit', $row->id).'">Edit</a>&nbsp&nbsp&nbsp<a href="'.route('user.delete', $row->id).'">Delete</a>';  
                $btn = view('layouts.actionbtnpermission')->with(['id'=>$row->id,'route'=>'product'])->render();           
                return $btn;
            })

            ->addColumn('school_id', function ($row) {
                $btn = $row->school->name;
                return $btn;
            })

            // ->addColumn('singlecheckbox', function ($row) {

            //         $schk = view('layouts.singlecheckbox')->with(['id' => $row->id , 'status'=>$row->status])->render();

            //         return $schk;

            //     })
            ->addColumn('singlecheckbox', function ($row) {

                $schk = view('layouts.singlecheckbox')->with(['id' => $row->id , 'status'=>$row->status])->render();

                return $schk;

            })

            ->rawColumns(['action','singlecheckbox'])
            ->make(true);
        } 

        return view('product.index');
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
            $data['school'] = School::get();

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

        $product = Product::create($param);
        if($product)
        {
            return response()->json(['status' => 'success','modules'=>'product','id'=>$product->id]);
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
        $data['id'] = $id;
        $data['title'] = 'Edit ' . $this->viewName;
        $data['data'] = Product::where('id', $id)->first();
        $data['module'] = $this->viewName;
        $data['resourcePath'] = $this->view;
        $data['school'] = School::get();
        $data['sizes'] = Size::orderBy('name')->get();

        $data['siz'] = Size::get()->toArray();
        $data['genders'] = Gender::orderBy('name')->get();

        $data['gen'] = Gender::get()->toArray();
        

        
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
        $data = Product::where('id', $id)->first();

        // $data = $request->all();
        // unset($data['_token'],$data['_method']);
        $data->school_id = $request->school_id;
        $data->name = $request->name;
        $data->code = $request->code;
        $data->gst = $request->gst;
        $data->save();

        // $data = Product::where('id', $id)->update($data);

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
    public function size_update(Request $request)

    {

        // $sizes = implode(',', $request->size_id);
        //     DB::table('product_size')
        //        ->updateOrInsert(
        //         ['product_id' => $request->product_id],
        //         ['size_id' => $sizes]
        //     );
     $param = $request->all();

     $product = Product::findOrFail($param['product_id']);



     $product->sizes()->sync($param['product']['size_id'], true);



     $res['status'] = 'success';

     $res['message'] = 'Size Save successfully';

     return response()->json($res);

 }

 public function gender_update(Request $request)

 {

    $param = $request->all();

    $product = Product::findOrFail($param['product_id']);

        // dd($param['product']['color_id']);


    $product->genders()->sync($param['product']['gender_id'], true);

    $res['status'] = 'success';

    $res['message'] = 'Gender Save successfully';



        // dd($product);



    return response()->json($res);

}

public function inventory_update(Request $request)
{
    $is_saved = $request->has('is_saved');

    if(!$is_saved)

    {

        $data = Product::findOrFail($request->product_id);

             // dd($product->toArray());

        $selected_lot = [];

        $selected_lot1 = [];

        $selected_lot2 = [];

        foreach($data->product_inventorys as $lot){

            $selected_lot[$lot['gender_id']][$lot['size_id']] = $lot['inventory'];

            $selected_lot1[$lot['gender_id']][$lot['size_id']] = $lot['min_order_qty'];

            $selected_lot2[$lot['gender_id']][$lot['size_id']] = $lot['max_order_qty'];

        }


        return view('product.inventory',compact('data','selected_lot','selected_lot1','selected_lot2'));

    } else{

        $product = Product::findOrFail($request->product_id);

        $params = $request->all();

        $save_array = [];

        if(isset($params['is_all']))

        {

            $Size = ProductSize::where('product_id',$request->product_id)->get()->toArray();

            $Gender = ProductGender::where('product_id',$request->product_id)->get()->toArray();

                // dd($params['min_order_qty'][$Color[0]['color_id']][$Size[0]['size_id']],$params['min_order_qty'],$Size[0],$Color[0]);

            foreach($Gender as $key => $value) {

                foreach($Size as $keys => $values) {

                    $save_array[$value['gender_id'].'_'.$values['size_id']] = new ProductInventory([

                        'product_id' => $request->product_id,

                        'gender_id' => $value['gender_id'],

                        'size_id' => $values['size_id'],

                        'min_order_qty' => isset($params['min_order_qty'][$gender[0]['gender_id']][$Size[0]['size_id']]) ? $params['min_order_qty'][$gender[0]['gender_id']][$Size[0]['size_id']] : 0,

                        'max_order_qty' => isset($params['max_order_qty'][$gender[0]['gender_id']][$Size[0]['size_id']]) ? $params['max_order_qty'][$gender[0]['gender_id']][$Size[0]['size_id']] : 0,

                        'inventory' => isset($params['quantity'][$gender[0]['gender_id']][$Size[0]['size_id']])? $params['quantity'][$gender[0]['gender_id']][$Size[0]['size_id']] : 0,

                    ]);

                }

            }

        }else{

            foreach($params['min_order_qty'] as $gender_id=>$min_qty) {

                foreach($min_qty as $size_id=>$qty) {

                    $save_array[$gender_id.'_'.$size_id] = new ProductInventory([

                        'product_id' => $request->product_id,

                        'gender_id' => $gender_id,

                        'size_id' => $size_id,

                        'min_order_qty' => $qty,

                        'max_order_qty' => $params['max_order_qty'][$gender_id][$size_id],

                        'inventory' => isset($params['quantity'][$gender_id][$size_id])? $params['quantity'][$gender_id][$size_id] : 0,

                    ]);

                }

            }

        }

        $product->product_inventorys()->delete();

        $product->product_inventorys()->saveMany($save_array, true);



        return response()->json(['status'=>'success']);

    }

}

public function price_update(Request $request)
{

        // echo "Sdfsdf";

        // exit;

    $is_saved = $request->has('is_saved');

    if(!$is_saved)

    {

        $data = Product::findOrFail($request->product_id);



        $selected_lot = [];

        $selected_lot_whole_sale = [];

        foreach($data->product_prices as $lot){

            $selected_lot[$lot['gender_id']][$lot['size_id']] = $lot['price'];

            $selected_lot_whole_sale[$lot['gender_id']][$lot['size_id']] = $lot['wholesale_price'];

                // $selected_lot_whole_sale_quantity[$lot['color_id']][$lot['size_id']] = $lot['wholesale_quantity'];

        }


        return view('product.price',compact('data', 'selected_lot', 'selected_lot_whole_sale'));

    } else{

            // dd($request->all());

        $product = Product::findOrFail($request->product_id);

        $params = $request->all();

        $save_array = [];

        if(isset($params['is_all']))

        {

            $Size = ProductSize::where('product_id',$request->product_id)->get()->toArray();

            $Gender = ProductGender::where('product_id',$request->product_id)->get()->toArray();



            foreach($Gender as $key => $value) {

             foreach($Size as $keys => $values) {

                $save_array[$value['gender_id'].'_'.$values['size_id']] = new ProductPrice([

                    'product_id' => $request->product_id,

                    'gender_id' => $value['gender_id'],

                    'size_id' => $values['size_id'],

                    'price' => isset($params['price'][$Gender[0]['gender_id']][$Size[0]['size_id']]) ? $params['price'][$Gender[0]['gender_id']][$Size[0]['size_id']] : 0,

                            // 'wholesale_quantity' => isset($params['wholesale_quantity'][$color_id][$size_id])? $params['wholesale_quantity'][$color_id][$size_id] : 0,

                    'wholesale_price' => isset($params['wholesale_price'][$Gender[0]['gender_id']][$Size[0]['size_id']])? $params['wholesale_price'][$Gender[0]['gender_id']][$Size[0]['size_id']] : 0,

                ]);

            }

        }

    }else{

        foreach($params['price'] as $gender_id=>$price) {

         foreach($price as $size_id=>$qty) {

            $save_array[$gender_id.'_'.$size_id] = new ProductPrice([

                'product_id' => $request->product_id,

                'gender_id' => $gender_id,

                'size_id' => $size_id,

                'price' => isset($params['price'][$gender_id][$size_id])? $params['price'][$gender_id][$size_id] : 0,

                            // 'wholesale_quantity' => isset($params['wholesale_quantity'][$color_id][$size_id])? $params['wholesale_quantity'][$color_id][$size_id] : 0,

                'wholesale_price' => isset($params['wholesale_price'][$gender_id][$size_id])? $params['wholesale_price'][$gender_id][$size_id] : 0,

            ]);

        }

    }

}    

$product->product_prices()->delete();

$product->product_prices()->saveMany($save_array, true);



return response()->json(['status'=>'success']);

}
}

public function sampleDownload(){
    return Excel::download(new ProductExport, 'products.xlsx');
}

public function productExport(){
    return Excel::download(new ProductDataExport, 'products.xlsx');
}

public function productsImport(Request $request){
    Excel::import(new ProductImport,request()->file('products'));
    return back()->with('success');
} 

public function ledgerindex(Request $request)
{
    $data['shop'] = Shop::get();
    $credit = 0;
    $debit = 0;
    if ($request->ajax()) {
        $query = Ledger::orderBy('id','DESC');
        if($request->shops)
        {
            $query = $query->where('shop_id',$request->shops);
        }
        if($request->from_date)
        {

            $query = $query->where('created_at','like','%'.$request->from_date.'%');
            
        }
            // if($request->to_date)
            // {

            //    $query = $query->whereDate('created_at','<=',$request->to_date);

            // }
        $query = $query->latest();
        return Datatables::of($query)

        ->addColumn('actual_amount', function ($row) {
            $btn = $row->credit - ($row->cgst + $row->sgst);
            return $btn;
        })

        ->rawColumns(['actual_amount'])
        ->make(true);
    } 
    return view('product.ledgerindex')->with($data);
}
public function ledgerindexnew(Request $request)
{
        // dd($request->all());
    $data = Ledger::orderBy('id','DESC');
    if($request->shops)
    {
        $data = $data->where('shop_id',$request->shop_id);
    }
    if($request->from_date && $request->to_date)
    {
        $data = $data->whereDate('created_at','>=',$request->from_date)->whereDate('created_at','<=',$request->to_date);
    }
    $data = $data->select('so_id')->get()->toArray();
    foreach ($data as $key => $value) {
        $sochild = So_child::where('so_id',$value['id'])->get();
        foreach ($sochild as $keys => $values) {

        }
    }
    $html = '<div class="row"><div class="col-lg-4"><table class="table table-striped- table-bordered table-hover table-checkable datatable"><thead><tr><th colspan="3">CREDIT</th></tr><tr><th>Total</th><th>CGST</th><th>Amount</th></tr></thead><tbody></tbody></table></div></div>';

    echo $html;
}
public function ledgerindexso(Request $request)
{
    $data['shop'] = Shop::get();
    return view('product.ledgerindexso')->with($data);
}
public function ledgerindexsodata(Request $request)
{
    $data = Ledger::orderBy('so_id');
    if($request->shops)
    {
        $data = $data->where('shop_id',$request->shops);
    }
    if($request->from_date && $request->to_date)
    {
        $data = $data->whereDate('created_at','>=',$request->from_date)->whereDate('created_at','<=',$request->to_date);
    }
    $data = $data->select('so_id')->get();
    

    $html = '<table class="table table-striped- table-bordered table-hover table-checkable datatable"><thead><tr><th>Order</th><th>Item</th><th>Quantity</th><th>Amount</th><th>CGST</th><th>SGST</th><th>CGST Amount</th><th>SGST Amount</th><th>Total</th></tr></thead><tbody>';
    $t_main_total = 0;
    $t_gst = 0;
    $total = 0;
    foreach ($data as $key => $value) {
        $so_child = So_child::where('so_id',$value->so_id)->get();
        $so_child1 = So_child::where('so_id',$value->so_id)->get()->toArray();
        if($so_child1)
        {
            foreach ($so_child as $keys => $values) {
                $product = Product::where('id',$values->product_id)->first();
                if($product->gst == $request->gstin)
                {
                    $gst = $values->amount-($values->amount*(100/(100+$product->gst)));
                    $main_amount = $values->amount - $gst;
                    $t_main_total += round($main_amount,2);
                    $t_gst += round($gst/2,2);
                    $total += round($values->amount,2);
                    $html .= '<tr><td>'.
                    SO::where('id',$value->so_id)->value('order_no')
                    // $value->so_id
                    .'</td><td>'.$product->name.'</td><td>'.$values->qty.'</td><td>'.round($main_amount,2).'</td><td>'.round(($product->gst)/2,2).'</td><td>'.round(($product->gst)/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.$values->amount.'</td></tr>';
                }
            }
        }
        if($request->gstin == 18)
        {
            $so_other = So_other::where('so_id',$value->so_id)->get();
            $so_other1 = So_other::where('so_id',$value->so_id)->get()->toArray();
            if($so_other1)
            {
                foreach ($so_other as $keys => $values)
                {
                    $gst = $values->amount-($values->amount*(100/(100+18)));
                    $main_amount = $values->amount - $gst;
                    $t_main_total += round($main_amount,2);
                    $t_gst += round($gst/2,2);
                    $total += round($values->amount,2);
                    $html .= '<tr><td>'.
                    SO::where('id',$value->so_id)->value('order_no')
                    // $value->so_id
                    .'</td><td>'.$values->name.'</td><td>'.$values->qty.'</td><td>'.round($main_amount,2).'</td><td>'.round((18)/2,2).'</td><td>'.round((18)/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.$values->amount.'</td></tr>';
                }
            }
        }
        if($request->gstin == 5)
        {
            $so_other2 = So_other::where('so_id',$value->so_id)->get();
            $so_other21 = So_other::where('so_id',$value->so_id)->get()->toArray();
            if($so_other21)
            {
                foreach ($so_other2 as $keys => $values)
                {
                    $gst = $values->amount-($values->amount*(100/(100+5)));
                    $main_amount = $values->amount - $gst;
                    $t_main_total += round($main_amount,2);
                    $t_gst += round($gst/2,2);
                    $total += round($values->amount,2);
                    $html .= '<tr><td>'.
                    SO::where('id',$value->so_id)->value('order_no')
                    // $value->so_id
                    .'</td><td>'.$values->name.'</td><td>'.$values->qty.'</td><td>'.round($main_amount,2).'</td><td>'.round((5)/2,2).'</td><td>'.round((5)/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.$values->amount.'</td></tr>';
                }
            }
        }
    }
    $html .= '<tr><td colspan="3">Total</td><td>'.$t_main_total.'</td><td></td><td></td><td>'.round($t_gst,2).'</td><td>'.round($t_gst,2).'</td><td>'.$total.'</td></tr></tbody></table>';

    echo $html;
}

public function ledgerindexsopdfdata(Request $request)
{

    $query = Ledger::orderBy('so_id');
    if($request->shops)
    {
        $query = $query->where('shop_id',$request->shops);
    }
    if($request->from_date && $request->to_date)
    {
        $query = $query->whereDate('created_at','>=',$request->from_date)->whereDate('created_at','<=',$request->to_date);
    }
    $query = $query->select('so_id')->get();

    // dd($query);
    $data['html'] = '<table class="table table-striped- table-bordered table-hover table-checkable datatable" style="width:100%"><thead><tr><th>Order</th><th>Item</th><th>Quantity</th><th>Amount</th><th>CGST</th><th>SGST</th><th>CGST Amount</th><th>SGST Amount</th><th>Total</th></tr></thead><tbody>';
    $t_main_total = 0;
    $t_gst = 0;
    $total = 0;
    foreach ($query as $key => $value) {

        $so_child = So_child::where('so_id',$value->so_id)->get();
        $so_child1 = So_child::where('so_id',$value->so_id)->get()->toArray();
        if($so_child1)
        {
            foreach ($so_child as $keys => $values) {
                $product = Product::where('id',$values->product_id)->first();
                if($product->gst == $request->gstin)
                {
                    $gst = $values->amount-($values->amount*(100/(100+$product->gst)));
                    $main_amount = $values->amount - $gst;
                    $t_main_total += round($main_amount,2);
                    $t_gst += round($gst/2,2);
                    $total += round($values->amount,2);
                    $data['html'] .= '<tr><td>'.$value->so_id.'</td><td>'.$product->name.'</td><td>'.$values->qty.'</td><td>'.round($main_amount,2).'</td><td>'.round(($product->gst)/2,2).'</td><td>'.round(($product->gst)/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.$values->amount.'</td></tr>';
                }
            }
        }
        if($request->gstin == 18)
        {
            $so_other = So_other::where('so_id',$value->so_id)->get();
            $so_other1 = So_other::where('so_id',$value->so_id)->get()->toArray();
            if($so_other1)
            {
                foreach ($so_other as $keys => $values)
                {
                    $gst = $values->amount-($values->amount*(100/(100+18)));
                    $main_amount = $values->amount - $gst;
                    $t_main_total += round($main_amount,2);
                    $t_gst += round($gst/2,2);
                    $total += round($values->amount,2);
                    $data['html'] .= '<tr><td>'.$value->so_id.'</td><td>'.$values->name.'</td><td>'.$values->qty.'</td><td>'.round($main_amount,2).'</td><td>'.round((18)/2,2).'</td><td>'.round((18)/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.$values->amount.'</td></tr>';
                }
            }
        }
        if($request->gstin == 5)
        {
            $so_other2 = So_other::where('so_id',$value->so_id)->get();
            $so_other21 = So_other::where('so_id',$value->so_id)->get()->toArray();
            if($so_other21)
            {
                foreach ($so_other2 as $keys => $values)
                {
                    $gst = $values->amount-($values->amount*(100/(100+5)));
                    $main_amount = $values->amount - $gst;
                    $t_main_total += round($main_amount,2);
                    $t_gst += round($gst/2,2);
                    $total += round($values->amount,2);
    // dd($values->name);
                    $data['html'] .= '<tr><td>'.$value->so_id.'</td><td>'.$values->name.'</td><td>'.$values->qty.'</td><td>'.round($main_amount,2).'</td><td>'.round((5)/2,2).'</td><td>'.round((5)/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.$values->amount.'</td></tr>';
                }
            }
        }
    }
    $data['html'] .= '<tr><td colspan="3">Total</td><td>'.$t_main_total.'</td><td></td><td></td><td>'.round($t_gst,2).'</td><td>'.round($t_gst,2).'</td><td>'.$total.'</td></tr></tbody></table>';
    // dd($data['html']);

    $pdf = PDF::setOptions(['logOutputFile' => storage_path('logs/log.htm'), 'tempDir' => storage_path('logs/'), 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('product.ledgerpdf', $data);

    return $pdf->download('DRS Ledger.pdf');
}
public function ledgerpdf(Request $request)
{
    $query = Ledger::orderBy('id','DESC');
    if($request->shops)
    {
        $query = $query->where('shop_id',$request->shops);
    }
    if($request->from_date)
    {

        $query = $query->where('created_at','like','%'.$request->from_date.'%');
        
    }
        // if($request->to_date)
        // {

        //    $query = $query->whereDate('created_at','<=',$request->to_date);

        // }
    $query = $query->get();
    $data['html'] = '<table style="width:100%"><tr  style=""><th  style="">Shop</th><th  style="">From date</th><th  style="">To date</th><th  style="">Total Actual Amount</th><th  style="">Total CGST</th><th  style="">Total SGSt</th><th  style="">Total Credit</th><th  style="">Total Debit</th><th  style="">Total Balance</th></tr><tr  style=""><td  style="">'.Shop::where('id',$request->shops)->value('name').'</td><td  style="">'.$request->from_date.'</td><td  style="">'.$request->to_date.'</td><td  style="">'.$request->actual.'</td><td  style="">'.$request->cgst.'</td><td  style="">'.$request->sgst.'</td><td  style="">'.$request->credit.'</td><td  style="">'.$request->debit.'</td><td  style="">'.$request->balance.'</td></tr></table><br><br>';
    $data['html'] .= '<table style="width:100%"><tr><th>Order No</th><th>Actual Amount</th><th>CGST</th><th>SGST</th><th>Credit</th><th>Debit</th></tr>';
    foreach($query as $q)
    {
        $actual = $q->credit - ($q->cgst + $q->sgst);
        $data['html'] .= '<tr><td>'.$q->so_id.'</td><td>'.$actual.'</td><td>'.$q->cgst.'</td><td>'.$q->sgst.'</td><td>'.$q->credit.'</td><td>'.$q->debit.'</td></tr>';
    }
    $data['html'] .= '</table>';
    $pdf = PDF::setOptions(['logOutputFile' => storage_path('logs/log.htm'), 'tempDir' => storage_path('logs/'), 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('product.ledgerpdf', $data);

    return $pdf->download('DRS Ledger.pdf');
}

}
