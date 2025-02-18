<?php

namespace App\Http\Controllers\Api\V1;

// use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;
use App\School;
use App\Product;
use App\Rawmaterial;
use App\Gender;
use App\Size;
use App\So;
use App\ProductInventory;
use App\So_child;
use App\ProductPrice;
use App\So_other;
use App\So_OtherTwo;
use App\So_OtherThree;
use App\B2b;
use App\B2b_child;
use App\SoRaw;
use App\Otherproduct;
use App\OtherProductTwo;
use App\OtherProductThree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Setting;
use App\Ledger;
use PDF;
use DB;

class UserController extends Controller
{
    public function accessories()
    {
        $data = Otherproduct::where('status',1)->get();

        return response()->json(['success' => 1, 'data' => $data]);
    }
    public function accessoriestwo()
    {
        $data = OtherProductTwo::where('status',1)->get();

        return response()->json(['success' => 1, 'data' => $data]);
    }
    public function accessoriesthree()
    {
        $data = OtherProductThree::where('status',1)->get();

        return response()->json(['success' => 1, 'data' => $data]);
    }
    public function getAuthenticatedUser()
    {
        $user = User::with('shop')->where('id',JWTAuth::user()->id)->first();

        return response()->json(compact('user'));
    }

    public function schooldetails(){
        $school = School::select('id','name')->orderBy('name')->get();
        return response()->json(['success' => 1, 'data'=> $school]);
    }

    public function schooldetailsoffline()
    {
        $schools = School::get();

        if (count($schools) > 0) {
            foreach ($schools as $key1 => $school) {

                $products = Product::where('school_id', $school->id)
                ->where('status', 1)
                ->with('product_inventorys', 'product_prices', 'sizes')
                ->get();

                foreach ($products as $key => $product) {
                    $productItems = [];
                    $items = [];

                    foreach ($product->product_inventorys as $key => $value) {
                        $used = $value->inventory - $value->used;
                        if ($used > 0) {
                            $items['gender_id'] = Gender::find($value->gender_id);
                            $items['size_id'] = Size::find($value->size_id);
                            $items['min_order_qty'] = $value->min_order_qty;
                            $items['max_order_qty'] = $value->max_order_qty;
                            $items['inventory'] = $value->inventory;
                            $items['used'] = $value->used;

                            $items['price'] = ProductPrice::where('product_id', $value->product_id)
                            ->where('gender_id', $value->gender_id)
                            ->where('size_id', $value->size_id)
                            ->value('price');
                            $items['wholesale_price'] = ProductPrice::where('product_id', $value->product_id)
                            ->where('gender_id', $value->gender_id)
                            ->where('size_id', $value->size_id)
                            ->value('wholesale_price');

                            array_push($productItems, $items);
                        }
                    }

                    if (!empty($productItems)) {
                        $product->itemsdetails = count($productItems) > 0 ? $productItems : '';
                    }

                    unset($product->product_inventorys, $product->product_prices, $product->sizes);
                }
                $school->products = $products;
            }

            return response()->json(['success' => 1, 'data' => $schools]);
        } else {
            return response()->json(['success' => 0, 'error' => 'School data not found!']);
        }
    }

    public function schoolproduct(Request $request){

        $school = School::where('id',$request->school_id)->first();
        if($school){
            $product = Product::where('school_id',$request->school_id)->where('status',1)->with('product_inventorys','product_prices','sizes')->get();

            foreach ($product as $key => $products) {
                $productitems = [];
                $items = [];

                foreach ($products->product_inventorys as $key => $value) {

                    $used = $value->inventory - $value->used;
                    if($used > 0){

                        $items['gender_id'] = Gender::find($value->gender_id);
                        $items['size_id'] = Size::find($value->size_id);
                        $items['min_order_qty'] = $value->min_order_qty;
                        $items['max_order_qty'] = $value->max_order_qty;
                        $items['inventory'] = $value->inventory;
                        $items['used'] = $value->used;

                        $items['price'] = ProductPrice::where('product_id',$value->product_id)->where('gender_id',$value->gender_id)->where('size_id',$value->size_id)->value('price');
                        $items['wholesale_price'] = ProductPrice::where('product_id',$value->product_id)->where('gender_id',$value->gender_id)->where('size_id',$value->size_id)->value('wholesale_price');
                        array_push($productitems,$items);
                    }
                }
                if(!empty($productitems)){
                    $products->itemsdetails = count($productitems) > 0 ? $productitems : '';
                }
                unset($products->product_inventorys,$products->product_prices,$products->sizes);
            }
            return response()->json(['success' => 1, 'data'=> $product]);
        } else {
            return response()->json(['success' => 0, 'error'=> 'School data not found!']);
        }
    }

    public function placeOrder(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        foreach ($request->product_id as $key => $value) {
            $ProductInventory = ProductInventory::where('product_id',$value)->where('gender_id',$request->gender_id[$key])->where('size_id',$request->size_id[$key])->first();
            $checkInv = '';
            if($ProductInventory->inventory > $ProductInventory->used){
                $newused = $ProductInventory->used + $request->qty[$key];
                if($newused < $ProductInventory->inventory){
                    $checkInv = 'true';
                } else {
                    $checkInv = 'false';
                }
            } else {
                $checkInv = 'false';
            }
        }
        if($checkInv == 'true'){
            $so = new So();
// $so->name = $request->name;
            $so->number = $request->number;
// $so->address = $request->address;
            $so->created_by = $user->id;

            $so->save();

            if(!empty($request->product_id)) {
                $totalAmount = [];
                $totalQty = [];
                $other_total_qty = [];
                $other_total_amount = [];

                foreach ($request->product_id as $key => $value) {

                    $product = Product::where('id',$value)->first();
                    $productPrice = ProductPrice::where('product_id',$value)->where('gender_id',$request->gender_id[$key])->where('size_id',$request->size_id[$key])->first();

                    $so_child = new So_child();
                    $so_child->so_id = $so->id;
                    $so_child->product_id = $request->product_id[$key];
                    $so_child->school_id = $product->school_id;
                    $so_child->qty = $request->qty[$key];
                    $so_child->price = $productPrice->price;
                    $so_child->wholesale_Price = $productPrice->wholesale_price;
                    $so_child->amount = $request->qty[$key] * $productPrice->wholesale_price;

                    $so_child->size_id = $request->size_id[$key];
                    $so_child->gender_id = $request->gender_id[$key];
                    $so_child->save();

                    $totalam = $so_child->qty * $productPrice->wholesale_price;
                    array_push($totalAmount,$totalam);
                    array_push($totalQty,$request->qty[$key]);

                    $ProductInventory = ProductInventory::where('product_id',$value)->where('gender_id',$request->gender_id[$key])->where('size_id',$request->size_id[$key])->first();
                    $addinused = $ProductInventory->used + $request->qty[$key];
                    $ProductInventory->update([
                        'used' => $addinused,
                    ]);
                }
                $otherTotal = 0;
                $otherQty = 0;
                if($request->other_name)
                {
                    foreach ($request->other_name as $key => $value) {
                        $so_other = new So_other();
                        $so_other->so_id = $so->id;
                        $so_other->name = $request->other_name[$key];
                        $so_other->qty = $request->other_qty[$key];
                        $so_other->price = $request->other_price[$key];
                        $so_other->amount = $request->other_qty[$key] * $request->other_price[$key];

                        $so_other->save();
                        $totalam = $request->other_qty[$key] * $request->other_price[$key];

                        array_push($other_total_amount,$totalam);
                        array_push($other_total_qty,$request->other_qty[$key]);

                        $otherTotal = array_sum($other_total_amount);
                        $otherQty = array_sum($other_total_qty);
                    }
                }

                $total = array_sum($totalAmount);
                $qty = array_sum($totalQty);


                $soupdate = So::where('id',$so->id)->first();
                $soupdate->update([
                    'total_amount' => $total,
                    'total_qty'  => $qty, 
                    'other_total_qty'  => $otherQty, 
                    'other_total_amount'  => $otherTotal, 
                    'grand_total_qty' => $qty + $otherQty, 
                    'grand_total_amount' => $total + $otherTotal, 
                ]);

                if($so) {
                    return response()->json(['success' => 1, 'message'=> "Order place successfully."]);
                } else {
                    return response()->json(['status' => 0,'error' => 'Failed to place order, please try again.']);
                }
            }
        } else {
            return response()->json(['status' => 0,'error' => 'Failed to place order, please try again.']);
        }
    }

    public function newplaceOrder(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
// dd($user);
        if($request->product_id || $request->other_name || $request->soraw_name || $request->other_name_two || $request->other_name_three){
            if(!isset($request->other_name) && !isset($request->soraw_name) && !isset($request->other_name_two)  && !isset($request->other_name_three)){
                if(count($request->product_id) > 0){
                    foreach ($request->product_id as $key => $value) {
                        $ProductInventory = ProductInventory::where('product_id',$value)->where('gender_id',$request->gender_id[$key])->where('size_id',$request->size_id[$key])->first();
                        $product_name = Product::where('id',$value)->value('name');
                        $checkInv = '';
                        if(isset($ProductInventory))
                        {
                            if($ProductInventory->inventory > $ProductInventory->used){
                                $newused = $ProductInventory->used + $request->qty[$key];
                                if($newused <= $ProductInventory->inventory){
                                    $checkInv = 'true';
                                } else {
                                    $checkInv = 'false';
                                    return response()->json(['status' => 0,'error' => 'Inventory not found '.$product_name]);
                                }
                            } else {
                                $checkInv = 'false';
                                return response()->json(['status' => 0,'error' => 'Inventory not found '.$product_name]);
                            }
                        }else{
                            return response()->json(['status' => 0,'error' => 'Inventory not found '.$product_name]);
                        }
                    }
                    if($checkInv == 'true'){
                        $get_last = So::orderBy('id','DESC')->first();
                        if(isset($get_last))
                        {
                            if($get_last->order_no)
                            {
                                $order_no = (integer)$get_last->order_no;
                            }else{
                                $order_no = 0;
                            }
                        }
                        else
                        {
                            $order_no = 0;
                        }
                        $so = new So();
                        $so->order_no = $order_no + 1 ;
                        $so->name = $request->name;
                        $so->number = $request->number;
// $so->address = $request->address;
                        $so->created_by = $user->id;
// dd($so);
                        $so->save();
                    }
                }
            } else {
                $get_last = So::orderBy('id','DESC')->first();
                if(isset($get_last))
                {
                    if($get_last->order_no)
                    {
                        $order_no = (integer)$get_last->order_no;
                    }else{
                        $order_no = 0;
                    }
                }
                else
                {
                    $order_no = 0;
                }
                $so = new So();
                $so->order_no = $order_no + 1 ;
                $so->name = $request->name;
                $so->number = $request->number;
// $so->address = $request->address;
                $so->created_by = $user->id;
// dd($so);
                $so->save();
            }
        }
        $total =0;
        $qty = 0;
        $gst = 0;
        if($request->product_id){
            foreach ($request->product_id as $key => $value) {
                $ProductInventory = ProductInventory::where('product_id',$value)->where('gender_id',$request->gender_id[$key])->where('size_id',$request->size_id[$key])->first();
                $checkInv = '';
                if($ProductInventory->inventory > $ProductInventory->used){
                    $newused = $ProductInventory->used + $request->qty[$key];
                    if($newused <= $ProductInventory->inventory){
                        $checkInv = 'true';
                    } else {
                        $checkInv = 'false';
                    }
                } else {
                    $checkInv = 'false';
                }
            }

            if($checkInv == 'true') {
                $totalAmount = [];
                $totalQty = [];


                foreach ($request->product_id as $key => $value) {

                    $product = Product::where('id',$value)->first();
                    $productPrice = ProductPrice::where('product_id',$value)->where('gender_id',$request->gender_id[$key])->where('size_id',$request->size_id[$key])->first();

                    $so_child = new So_child();
                    $so_child->so_id = $so->id;
                    $so_child->product_id = $request->product_id[$key];
                    $so_child->school_id = $product->school_id;
                    $so_child->qty = $request->qty[$key];
                    $so_child->price = $productPrice->price;
                    $so_child->wholesale_Price = $productPrice->wholesale_price;
                    $so_child->amount = $request->qty[$key] * $productPrice->wholesale_price;
// $so_child->amount = $productPrice->wholesale_price;

                    $so_child->size_id = $request->size_id[$key];
                    $so_child->gender_id = $request->gender_id[$key];
                    $so_child->save();

                    $totalam = $so_child->qty * $productPrice->wholesale_price;
                    array_push($totalAmount,$totalam);
                    array_push($totalQty,$request->qty[$key]);

                    $ProductInventory = ProductInventory::where('product_id',$value)->where('gender_id',$request->gender_id[$key])->where('size_id',$request->size_id[$key])->first();
                    $addinused = $ProductInventory->used + $request->qty[$key];
                    $ProductInventory->update([
                        'used' => $addinused,
                    ]);

                    $gst = $gst + ((($product->gst*$productPrice->wholesale_price)/100)*$request->qty[$key]);
                }
                $total = array_sum($totalAmount);
                $qty = array_sum($totalQty);


            }

        }

        $otherTotal = 0;
        $otherQty = 0;
        if($request->other_name)
        {
            $other_total_qty = [];
            $other_total_amount = [];
            foreach ($request->other_name as $key => $value) {
                $so_other = new So_other();
                $so_other->so_id = $so->id;
                $so_other->name = $request->other_name[$key];
                $so_other->qty = $request->other_qty[$key];
                $so_other->price = $request->other_price[$key];
                $so_other->amount = $request->other_qty[$key] * $request->other_price[$key];
// $so_other->amount = $request->other_price[$key];

                $so_other->save();
                $totalam = $request->other_qty[$key] * $request->other_price[$key];
// $totalam = $request->other_price[$key];

                array_push($other_total_amount,$totalam);
                array_push($other_total_qty,$request->other_qty[$key]);

                $otherTotal = array_sum($other_total_amount);
                $otherQty = array_sum($other_total_qty);

                $gst = $gst + (((5*$request->other_price[$key])/100)*$request->other_qty[$key]);
            }
        }

        $otherTotaltwo = 0;
        $otherQtytwo = 0;
        if($request->other_name_two)
        {
            $other_total_qty_two = [];
            $other_total_amount_two = [];
            foreach ($request->other_name_two as $key => $value) {
                $so_other = new So_OtherTwo();
                $so_other->so_id = $so->id;
                $so_other->name = $request->other_name_two[$key];
                $so_other->qty = $request->other_qty_two[$key];
                $so_other->price = $request->other_price_two[$key];
                $so_other->amount = $request->other_qty_two[$key] * $request->other_price_two[$key];
// $so_other->amount = $request->other_price_two[$key];

                $so_other->save();
                $totalam = $request->other_qty_two[$key] * $request->other_price_two[$key];
// $totalam = $request->other_price_two[$key];

                array_push($other_total_amount_two,$totalam);
                array_push($other_total_qty_two,$request->other_qty_two[$key]);

                $otherTotaltwo = array_sum($other_total_amount_two);
                $otherQtytwo = array_sum($other_total_qty_two);

                $gst = $gst + (((18*$request->other_price_two[$key])/100)*$request->other_qty_two[$key]);
            }
        }
        $otherTotalthree = 0;
        $otherQtythree = 0;
        if($request->other_name_three)
        {
            $other_total_qty_three = [];
            $other_total_amount_three = [];
            foreach ($request->other_name_three as $key => $value) {
                $so_other = new So_OtherThree();
                $so_other->so_id = $so->id;
                $so_other->name = $request->other_name_three[$key];
                $so_other->qty = $request->other_qty_three[$key];
                $so_other->price = $request->other_price_three[$key];
                $so_other->amount = $request->other_qty_three[$key] * $request->other_price_three[$key];
// $so_other->amount = $request->other_price_three[$key];

                $so_other->save();
                $totalam = $request->other_qty_three[$key] * $request->other_price_three[$key];
// $totalam = $request->other_price_three[$key];

                array_push($other_total_amount_three,$totalam);
                array_push($other_total_qty_three,$request->other_qty_three[$key]);

                $otherTotalthree = array_sum($other_total_amount_three);
                $otherQtythree = array_sum($other_total_qty_three);

                $gst = $gst + (((12*$request->other_price_three[$key])/100)*$request->other_qty_three[$key]);
            }
        }
        $sorawQty = 0;
        $sorawTotal = 0;
        if($request->soraw_name)
        {
            $soraw_total_qty = [];
            $soraw_total_amount = [];
            foreach ($request->soraw_name as $key => $value) {
                $so_raw = new SoRaw();
                $so_raw->so_id = $so->id;
                $so_raw->name = $value;
                $so_raw->qty = $request->soraw_qty[$key];
                $so_raw->price = $request->soraw_price[$key];
                $so_raw->amount = $request->soraw_qty[$key] * $request->soraw_price[$key];
// $so_raw->amount = $request->soraw_price[$key];

                $so_raw->save();
                $totalam = $request->soraw_qty[$key] * $request->soraw_price[$key];

                array_push($soraw_total_amount,$totalam);
                array_push($soraw_total_qty,$request->soraw_qty[$key]);

                $sorawTotal = array_sum($soraw_total_amount);
                $sorawQty = array_sum($soraw_total_qty);

                $gst = $gst + (((5*$request->soraw_price[$key])/100)*$request->soraw_qty[$key]);
            }
        }

        if($so) {
            $soupdate = So::where('id',$so->id)->first();
            $soupdate->update([
                'total_amount' => $total,
                'total_qty'  => $qty, 
                'other_total_qty'  => $otherQty, 
                'other_total_amount'  => $otherTotal,
                'other_total_qty_two'  => $otherQtytwo,
                'other_total_amount_two'  => $otherTotaltwo,
                'other_total_qty_three'  => $otherQtythree,
                'other_total_amount_three'  => $otherTotalthree,
                'soraw_total_qty'  => $sorawQty, 
                'soraw_total_amount'  => $sorawTotal,
                'grand_total_qty' => $qty + $otherQty + $sorawQty + $otherQtytwo + $otherQtythree, 
                'grand_total_amount' => $total + $otherTotal + $sorawTotal + $otherTotaltwo + $otherTotalthree,
                'grand_total_qty_two' => $qty + $otherQtytwo + $sorawQty, 
                'grand_total_amount_two' => $total + $otherTotaltwo + $sorawTotal,
            ]);

            $param_ledger = [
                'so_id' => $so->id,
                'created_by' => $user->id,
                'shop_id' => $user->shop_id,
                'cgst' => round(($gst/2),2),
                'sgst' => round(($gst/2),2),
                'credit' => $soupdate->grand_total_amount,
            ];
            $ledger = Ledger::create($param_ledger);
            $gst = 0;
            $order = So::with('otherDataThree','otherDataTwo','childData','otherData','sorawData','childData.schools','childData.sizes','childData.genders')->where('id',$so->id)->first();
            foreach ($order->childData as $key => $value) {
                $gst = $gst + $value->gst_amount;
            }
            foreach ($order->otherDataTwo as $key => $value) {
                $gst = $gst + $value->gst_amount;
            }
            foreach ($order->otherData as $key => $value) {
                $gst = $gst + $value->gst_amount;
            }
            foreach ($order->otherDataThree as $key => $value) {
                $gst = $gst + $value->gst_amount;
            }
            $order->cgst_amount = round($gst/2,2);
            $order->sgst_amount = round($gst/2,2);
            return response()->json(['success' => 1, 'message'=> "Order place successfully.", 'order' => $order]);
        } else {
            return response()->json(['status' => 0,'error' => 'Failed to place order, please try again.']);
        }
    }
    public function neworderDetail(Request $request)
    {

        $user = JWTAuth::parseToken()->authenticate();
        $order = So::with('childData','otherData','otherDataTwo','otherDataThree','sorawData','childData.schools','childData.sizes','childData.genders');
        if($request->search)
        {
            $order->where('order_no',$request->search);
        }
        if(!($user->role == 3))
        {
            $order->where('status',0);
        }else{
            $order->where('created_by',$user->id)->where('status',0);
        }
        $order = $order->orderBy('id','DESC')->paginate();
        foreach ($order as $keys => $orders) {
            $gst = 0;
            $gstamt = 0;
            foreach ($orders->childData as $key => $value) {
                $gst = $gst + $value->gst_amount;
                $gstamt = $gstamt + $value->total_amount_before_gst;
            }
            foreach ($orders->otherData as $key => $value) {
                $gst = $gst + $value->gst_amount;
                $gstamt = $gstamt + $value->total_amount_before_gst;
            }
            foreach ($orders->otherDataTwo as $key => $value) {
                $gst = $gst + $value->gst_amount;
                $gstamt = $gstamt + $value->total_amount_before_gst;
            }
            foreach ($orders->otherDataThree as $key => $value) {
                $gst = $gst + $value->gst_amount;
                $gstamt = $gstamt + $value->total_amount_before_gst;
            }
            foreach ($orders->sorawData as $key => $value) {
                $gst = $gst + $value->gst_amount;
                $gstamt = $gstamt + $value->total_amount_before_gst;
            }
            $orders->cgst_amount = round($gst/2,2);
            $orders->sgst_amount = round($gst/2,2);
            $orders->main_gst_amt = round($gstamt/2,2);
        }
        return response()->json(['success' => 1, 'data'=> $order]);

    }
    public function orderDetail(){

        $user = JWTAuth::parseToken()->authenticate();
        if(!($user->role == 3))
        {
            $order = So::with('childData','otherData','otherDataTwo','otherDataThree','sorawData','childData.schools','childData.sizes','childData.genders')->where('status',0)->orderBy('id','DESC')->paginate();
        }else{
            $order = So::with('childData','otherData','otherDataTwo','otherDataThree','sorawData','childData.schools','childData.sizes','childData.genders')->where('created_by',$user->id)->where('status',0)->orderBy('id','DESC')->paginate();
        }
        foreach ($order as $keys => $orders) {
            $gst = 0;
            $gstamt = 0;
            foreach ($orders->childData as $key => $value) {
                $gst = $gst + $value->gst_amount;
                $gstamt = $gstamt + $value->total_amount_before_gst;
            }
            foreach ($orders->otherData as $key => $value) {
                $gst = $gst + $value->gst_amount;
                $gstamt = $gstamt + $value->total_amount_before_gst;
            }
            foreach ($orders->otherDataTwo as $key => $value) {
                $gst = $gst + $value->gst_amount;
                $gstamt = $gstamt + $value->total_amount_before_gst;
            }
            foreach ($orders->otherDataThree as $key => $value) {
                $gst = $gst + $value->gst_amount;
                $gstamt = $gstamt + $value->total_amount_before_gst;
            }
            foreach ($orders->sorawData as $key => $value) {
                $gst = $gst + $value->gst_amount;
                $gstamt = $gstamt + $value->total_amount_before_gst;
            }
            $orders->cgst_amount = round($gst/2,2);
            $orders->sgst_amount = round($gst/2,2);
            $orders->main_gst_amt = round($gstamt/2,2);
        }
        return response()->json(['success' => 1, 'data'=> $order]);

    }

    public function B2BPlaceOrder(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'number' => 'required',
            'address' => 'required',
            'product_name' => 'required',
            'product_price' => 'required',
            'product_qty' => 'required',
// 'gst' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = JWTAuth::parseToken()->authenticate();
// dd($user->id);
        $b2b = new B2b;
        $b2b->name = $request->name;
        $b2b->email = $request->email ?? NULL;
        $b2b->number = $request->number;
        $b2b->address = $request->address;
        $b2b->address2 = $request->address2 ?? NULL;
        $b2b->address3 = $request->address3 ?? NULL;
        $b2b->gstin = $request->gstin ?? NULL;
        $b2b->pan_no = $request->pan_no ?? NULL;
        $b2b->status = 0;
        $b2b->created_by = $user->id;
        $b2b->save();

        $totalAmount = [];
        $totalQty = [];

        foreach ($request->product_name as $key => $value) {

            $B2bchild = B2b_child::create([
                'b2b_id' => $b2b->id,
                'name' => $value,
                'price' => $request->product_price[$key],
                'qty' => $request->product_qty[$key],
                'amount' => $request->product_qty[$key] * $request->product_price[$key],
                'gst' => (isset($request->gst[$key])) ? ($request->gst[$key]) : 0,
                'gst_amount' => (isset($request->gst[$key])) ? (round(($request->gst[$key] * $request->product_qty[$key] * $request->product_price[$key])/100)) : 0,
                'total' => (isset($request->gst[$key])) ? ((round(($request->gst[$key] * $request->product_qty[$key] * $request->product_price[$key])/100))+($request->product_qty[$key] * $request->product_price[$key])) : ($request->product_qty[$key] * $request->product_price[$key]),
// 'created_by' => $user->id,
            ]);

            $total = $B2bchild->total;
            array_push($totalAmount,$total);
            array_push($totalQty,$request->product_qty[$key]);
        }

        $Totalb2bAmount = array_sum($totalAmount);
        $Totalb2bQty = array_sum($totalQty);

        $b2bupdate = B2b::find($b2b->id);
        $b2bupdate->update([
            'total_qty' => $Totalb2bQty,
            'total_amount' => $Totalb2bAmount,
        ]);

        return response()->json(['success' => 1, 'message'=> "Order place successfully."]);

    }

    public function B2BOrderDetail(){

        $user = JWTAuth::parseToken()->authenticate();
        if($user){
            $b2border = B2b::where('created_by', $user->id)->where('status',0)->with('edit_clone')->orderBy('id','DESC')->get();
            return response()->json(['success' => 1, 'data'=> $b2border]);
        } else {
            return response()->json(['success' => 0, 'message'=> "User data not found!"]);
        }

    }

    public function logout(Request $request) {
        try {
            $token = $request->header('Authorization');
            JWTAuth::invalidate($token);
            return response()->json(['success' => 1, 'message'=> "You have successfully logged out."]);
        } catch (JWTException $e) {
            return response()->json(['success' => 0, 'error' => 'Failed to logout, please try again.'], 500);
        }
    }

    public function inventoryview()
    {
        $data = ProductInventory::with('Products.school','Products','Genders','sizes')->get();

        return response()->json(['success' => 1, 'data' => $data]);
    }
    public function inventoryupdate(Request $request)
    {
        if($request->id)
        {
            foreach ($request->id as $key => $value) {
                $inventory = ProductInventory::where('id',$value)->first();
                $update = $inventory->inventory + $request->inventory[$key];
                $inventory->inventory = $update;
                $inventory->save();

            }
        }

        return response()->json(['success' => 1, 'message' => 'Inventory update successfully']);
    }

    public function B2BInvoice($id)
    {
        $data['datas'] = B2b::where('id',$id)->first();
        $setting = Setting::where('id',1)->first();
        $inv = B2b::where('id',$id)->first();

        $data['data'] ='<table style="width:100%"><tr><th colspan="5" style="border-collapse:collapse !important;border: none !important;text-align:left;color:#7545BC;font-size: 18px" bgcolor="#E8E8E8">&nbspInvoice From</th>
        <td style="border-collapse:collapse !important;border: none !important;width:1%"><th style="border-collapse:collapse !important;border: none !important;text-align:left;color:#7545BC;font-size: 18px" colspan="5" bgcolor="#E8E8E8"> &nbspInvoice For</th></td></tr>
        <td colspan="5" style="border-collapse:collapse !important;border: none !important;" bgcolor="#E8E8E8"> <b> &nbsp'.$setting->name.'</b><br>&nbsp'.$setting->address.' <br>&nbsp'.$setting->address2.'<br>&nbsp'.$setting->address3.'<br><b>&nbspEmail&nbsp:&nbsp</b>'.$setting->email.' </b><br> <b>&nbspPhone&nbsp:&nbsp</b>'.$setting->contact.'</td>

        <td style="border-collapse:collapse !important;border: none !important;"><td colspan="5" width="135px" style="border-collapse:collapse !important;border: none !important;" bgcolor="#E8E8E8"><b>&nbsp '.$inv->name.'</b><br>&nbsp&nbsp'.$inv->address.'<br>&nbsp '.$inv->address2.' <br>&nbsp&nbsp'.$inv->address3.'<br><b>&nbsp GSTIN&nbsp:&nbsp</b>'.$inv->gstin.'<br><b>&nbsp&nbspPAN&nbsp:&nbsp</b>'.$inv->pan_no.  '<br><b>&nbsp&nbspEmail&nbsp:</b>&nbsp'.$inv->email.'<br><b>&nbsp&nbspPhone&nbsp:</b>&nbsp'.$inv->number.'</td></td>
        </table><br>

        <table style="width:100%"><tr><th style="text-align:left;border-collapse:collapse !important;border: none !important;color: white;width:50px" bgcolor="#7545BC">&nbspNo.</th>
        <th style="text-align:left;border-collapse:collapse !important;border: none !important;color: white" bgcolor="#7545BC"><b>Item</b></th>
        <th style="text-align:left;border-collapse:collapse !important;border: none !important;color: white" bgcolor="#7545BC"><b>Price</b></th>
        <th style="text-align:center;border-collapse:collapse !important;border: none !important;color: white" bgcolor="#7545BC"><b>QTY</b></th>
        <th style="text-align:center;border-collapse:collapse !important;border: none !important;color: white" bgcolor="#7545BC"><b>GST(%)</b></th>
        <th style="text-align:center;border-collapse:collapse !important;border: none !important;color: white" bgcolor="#7545BC"><b>GST Amount</b></th>
        <th style="text-align:right;border-collapse:collapse !important;border: none !important;color: white" bgcolor="#7545BC"><b>Total Amount</b></th></tr>';

        $so_child = B2b_child::where('b2b_id',$id)->get();
        $invs = B2b::where('id',$id)->first();
        $count = 0;
        $number = 1;
        foreach ($so_child as $key=>$value) 
        {
            $t = $invs->total_amount;
            $counts = $number++;
            $data['data'] .= '<tr><td style="text-align:left;width:15%;border-collapse:collapse !important;border: none !important" bgcolor="#E8E8E8">&nbsp'.$counts.'</td> 
            <td style="text-align:left;border-collapse:collapse !important;border: none !important" bgcolor="#E8E8E8;width:150px">'.$value->name.'</td>
            <td style="text-align:left;border-collapse:collapse !important;border: none !important" bgcolor="#E8E8E8">'.$value->price.'</td>
            <td style="text-align:center;width:10%;border-collapse:collapse !important;border: none !important" bgcolor="#E8E8E8">'.$value->qty.'</td>
            <td style="text-align:center;width:10%;border-collapse:collapse !important;border: none !important" bgcolor="#E8E8E8">'.$value->gst.'</td>
            <td style="text-align:center;width:20%;border-collapse:collapse !important;border: none !important" bgcolor="#E8E8E8">'.$value->gst_amount.'</td>
            <td style="text-align:right;width:20%;border-collapse:collapse !important;border: none !important" bgcolor="#E8E8E8">'.$value->total.'</td></tr>'; 
            $count = $t;
        }

        function numberTowords(float $amount)
        {
            $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
            $amt_hundred = null;
            $count_length = strlen($num);
            $x = 0;
            $string = array();
            $change_words = array(0 => '', 1 => 'One', 2 => 'Two',
                3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
                7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
                10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
                13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
                16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
                19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
                40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
                70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
            $here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
            while( $x < $count_length ) 
            {
                $get_divider = ($x == 2) ? 10 : 100;
                $amount = floor($num % $get_divider);
                $num = floor($num / $get_divider);
                $x += $get_divider == 10 ? 1 : 2;
                if ($amount)
                {
                    $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
                    $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
                    $string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.' 
                    '.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. ' 
                    '.$here_digits[$counter].$add_plural.' '.$amt_hundred;
                }else $string[] = null;
            }  
            $implode_to_Rupees = implode('', array_reverse($string));
            $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 
                " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';
            return ($implode_to_Rupees ? $implode_to_Rupees . 'Only ' : '') . $get_paise;
        }

        $data['data'].='<tr><td style="float:right;border-collapse:collapse !important;border: none !important"></td><th colspan="2" style="text-align:right;border-collapse:collapse !important;border: none !important">Total(INR)</th><th style="border-collapse:collapse !important;border: none !important;text-align:right">'.$inv->total_amount.'</th>
        </th></tr><br><br>';

        $data['data'].='</table><b>Total (in words)&nbsp:</b>&nbsp<font size="14px">'.numberTowords($count).' </font><br><br><br>
        <p style="text-align:left;color:#7545BC">Additional Notes</p>
        <span style="text-align:left;">The final amount is inclusive of GST</span><br><br><br>
        <p style="text-align:Center;">For any enquiry, reach out via email at <b>'.$setting->email.'</b>, call on <b>'.$setting->contact.'</b></p>
        <p style=" position: absolute; bottom: 0; left: 0; width: 100%; text-align: center;">This is an electronically generated document, no signature is required.</p>';

        $pdf = PDF::setOptions(['logOutputFile' => storage_path('logs/log.htm'), 'tempDir' => storage_path('logs/'), 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf', $data)->setPaper('A4', 'portrait');

        $certificate_name = $id.'-DRS.pdf';
        $pdf->save(public_path('b2b/' . $certificate_name));

        return response()->json(['success' => 1, 'data' => asset('/b2b/'.$certificate_name) ]);
    }

    public function getrawmaterial(){
        $rm = Rawmaterial::get();
        return response()->json(['success' => 1, 'data'=> $rm]);
    }

    // public function deleteChildOrder(Request $request)
    // {
    //     if(!($request->qty))
    //     {
    //         return response()->json(['success' => 0, 'message' => 'required qty']);
    //     }
    //     if(!($request->id))
    //     {
    //         return response()->json(['success' => 0, 'message' => 'required id']);
    //     }
    //     if(!($request->type_id))
    //     {
    //         return response()->json(['success' => 0, 'message' => 'required type_id']);
    //     }
    //     if($request->type_id==1){
    //         $soChild = So_child::where('id',$request->id)->first();

    //         if($soChild){
    //             if(!($soChild->qty >= $request->qty))
    //             {
    //                 return response()->json(['status' => 0, 'message' => 'qty exceeds.']);
    //             }
    //             $ProductInventory = ProductInventory::where('product_id',$soChild->product_id)->where('gender_id',$soChild->gender_id)->where('size_id',$soChild->size_id)->first();
    //             $ProductInventory->update([
    //                 'inventory' => $ProductInventory->inventory + $request->qty,
    //                 'used' => $ProductInventory->used - $request->qty,
    //             ]);
    //             $so = SO::with('users')->where('id',$soChild->so_id)->first();
    //             $old = $so->grand_total_amount;
    //             $so->update([
    //                 'total_qty' => $so->total_qty - $request->qty,
    //                 'total_amount' => $so->total_amount - ($request->qty * $soChild->wholesale_Price),
    //                 'grand_total_qty' => $so->grand_total_qty - $request->qty,
    //                 'grand_total_amount' => $so->grand_total_amount - ($request->qty * $soChild->wholesale_Price),
    //             ]);
    //             $new = $so->grand_total_amount;
    //             $newold = $old - $new;
    //             $pro = Product::where('id',$soChild->product_id)->first();
    //             $gst = (($pro->gst*$soChild->wholesale_Price)/100)*$request->qty;
    //             $ledger_param = [
    //                 'so_id' => $so->id,
    //                 'created_by' => $so->created_by,
    //                 'shop_id' => $so->users->shop_id,
    //                 'cgst' => round(($gst/2),2),
    //                 'sgst' => round(($gst/2),2),
    //                 'debit' => $newold
    //             ];
    //             Ledger::create($ledger_param);
    //             if($request->qty == $soChild->qty)
    //             {
    //                 $soChild->delete();
    //             }else{
    //                 $soChild->qty = $soChild->qty - $request->qty;
    //                 $soChild->amount = $soChild->amount - ($request->qty * $soChild->wholesale_Price);
    //                 $soChild->save();
    //             }

    //             return response()->json(['success' => 1, 'message'=> 'So child data delete successfully!']);
    //         } else {
    //             return response()->json(['success' => 0, 'message'=> 'Data not found!']);
    //         }
    //     }
    //     if($request->type_id==2){
    //         $soOther = So_other::where('id',$request->id)->first();
    //         if($soOther){
    //             if(!($soOther->qty >= $request->qty))
    //             {
    //                 return response()->json(['status' => 0, 'message' => 'qty exceeds.']);
    //             }
    //             $so = SO::with('users')->where('id',$soOther->so_id)->first();
    //             $old = $so->grand_total_amount;
    //             $so->update([
    //                 'other_total_qty' => $so->other_total_qty - $request->qty,
    //                 'other_total_amount' => $so->other_total_amount - ($request->qty * ($soOther->price/$soOther->qty)),
    //                 'grand_total_qty' => $so->grand_total_qty - $request->qty,
    //                 'grand_total_amount' => $so->grand_total_amount - ($request->qty * ($soOther->price/$soOther->qty)),
    //             ]);
    //             $new = $so->grand_total_amount;
    //             $newold = $old - $new;
    //             $gst = ((18*($soOther->price/$soOther->qty))/100)*$request->qty;
    //             $ledger_param = [
    //                 'so_id' => $so->id,
    //                 'created_by' => $so->created_by,
    //                 'shop_id' => $so->users->shop_id,
    //                 'cgst' => round(($gst/2),2),
    //                 'sgst' => round(($gst/2),2),
    //                 'debit' => $newold
    //             ];
    //             Ledger::create($ledger_param);
    //             if($request->qty == $soOther->qty)
    //             {
    //                 $soOther->delete();
    //             }else{
    //                 $soOther->qty = $soOther->qty - $request->qty;
    //                 $soOther->amount = $soOther->amount - ($request->qty * ($soOther->price/$soOther->qty));
    //                 $soOther->save();
    //             }
    //             return response()->json(['success' => 1, 'message'=> 'So other data delete successfully!']);
    //         } else {
    //             return response()->json(['success' => 0, 'message'=> 'Data not found!']);
    //         }
    //     }
    //     if($request->type_id==3){
    //         $soRaw = SoRaw::where('id',$request->id)->first();
    //         if($soRaw){
    //             if(!($soRaw->qty >= $request->qty))
    //             {
    //                 return response()->json(['status' => 0, 'message' => 'qty exceeds.']);
    //             }
    //             $so = SO::with('users')->where('id',$soRaw->so_id)->first();
    //             $old = $so->grand_total_amount;
    //             $so->update([
    //                 'soraw_total_qty' => $so->soraw_total_qty - $request->qty,
    //                 'soraw_total_amount' => $so->soraw_total_amount - ($request->qty * ($soRaw->amount/$soRaw->qty)),
    //                 'grand_total_qty' => $so->grand_total_qty - $request->qty,
    //                 'grand_total_amount' => $so->grand_total_amount - ($request->qty * ($soRaw->amount/$soRaw->qty)),
    //             ]);
    //             $new = $so->grand_total_amount;
    //             $newold = $old - $new;
    //             $ledger_param = [
    //                 'so_id' => $so->id,
    //                 'created_by' => $so->created_by,
    //                 'shop_id' => $so->users->shop_id,
    //                 'debit' => $newold
    //             ];
    //             Ledger::create($ledger_param);
    //             if($request->qty == $soRaw->qty)
    //             {
    //                 $soRaw->delete();
    //             }else{
    //                 $soRaw->qty = $soRaw->qty - $request->qty;
    //                 $soRaw->amount = $soRaw->amount - ($request->qty * ($soRaw->amount/$soRaw->qty));
    //                 $soRaw->save();
    //             }
    //             return response()->json(['success' => 1, 'message'=> 'So raw data delete successfully!']);

    //         } else {
    //             return response()->json(['success' => 0, 'message'=> 'Data not found!']);
    //         }
    //     }

    //     if($request->type_id==4){
    //         $soOther = So_OtherTwo::where('id',$request->id)->first();
    //         if($soOther){
    //             if(!($soOther->qty >= $request->qty))
    //             {
    //                 return response()->json(['status' => 0, 'message' => 'qty exceeds.']);
    //             }
    //             $so = SO::with('users')->where('id',$soOther->so_id)->first();
    //             $old = $so->grand_total_amount;
    //             $so->update([
    //                 'other_total_qty_two' => $so->other_total_amount_two - $request->qty,
    //                 'other_total_amount_two' => $so->other_total_amount_two - ($request->qty * ($soOther->price/$soOther->qty)),
    //                 'grand_total_qty' => $so->grand_total_qty - $request->qty,
    //                 'grand_total_amount' => $so->grand_total_amount - ($request->qty * ($soOther->price/$soOther->qty)),
    //             ]);
    //             $new = $so->grand_total_amount;
    //             $newold = $old - $new;
    //             $gst = ((5*($soOther->price/$soOther->qty))/100)*$request->qty;
    //             $ledger_param = [
    //                 'so_id' => $so->id,
    //                 'created_by' => $so->created_by,
    //                 'shop_id' => $so->users->shop_id,
    //                 'cgst' => round(($gst/2),2),
    //                 'sgst' => round(($gst/2),2),
    //                 'debit' => $newold
    //             ];
    //             Ledger::create($ledger_param);
    //             if($request->qty == $soOther->qty)
    //             {
    //                 $soOther->delete();
    //             }else{
    //                 $soOther->qty = $soOther->qty - $request->qty;
    //                 $soOther->amount = $soOther->amount - ($request->qty * ($soOther->price/$soOther->qty));
    //                 $soOther->save();
    //             }
    //             return response()->json(['success' => 1, 'message'=> 'Delete successfully!']);
    //         } else {
    //             return response()->json(['success' => 0, 'message'=> 'Data not found!']);
    //         }
    //     }
    //     if($request->type_id==5){
    //         $soOther = So_OtherThree::where('id',$request->id)->first();
    //         if($soOther){
    //             if(!($soOther->qty >= $request->qty))
    //             {
    //                 return response()->json(['status' => 0, 'message' => 'qty exceeds.']);
    //             }
    //             $so = SO::with('users')->where('id',$soOther->so_id)->first();
    //             $old = $so->grand_total_amount;
    //             $so->update([
    //                 'other_total_qty_two' => $so->other_total_amount_two - $request->qty,
    //                 'other_total_amount_two' => $so->other_total_amount_two - ($request->qty * ($soOther->price/$soOther->qty)),
    //                 'grand_total_qty' => $so->grand_total_qty - $request->qty,
    //                 'grand_total_amount' => $so->grand_total_amount - ($request->qty * ($soOther->price/$soOther->qty)),
    //             ]);
    //             $new = $so->grand_total_amount;
    //             $newold = $old - $new;
    //             $gst = ((5*($soOther->price/$soOther->qty))/100)*$request->qty;
    //             $ledger_param = [
    //                 'so_id' => $so->id,
    //                 'created_by' => $so->created_by,
    //                 'shop_id' => $so->users->shop_id,
    //                 'cgst' => round(($gst/2),2),
    //                 'sgst' => round(($gst/2),2),
    //                 'debit' => $newold
    //             ];
    //             Ledger::create($ledger_param);
    //             if($request->qty == $soOther->qty)
    //             {
    //                 $soOther->delete();
    //             }else{
    //                 $soOther->qty = $soOther->qty - $request->qty;
    //                 $soOther->amount = $soOther->amount - ($request->qty * ($soOther->price/$soOther->qty));
    //                 $soOther->save();
    //             }
    //             return response()->json(['success' => 1, 'message'=> 'Delete successfully!']);
    //         } else {
    //             return response()->json(['success' => 0, 'message'=> 'Data not found!']);
    //         }
    //     }
    // }

    public function deleteChildOrder(Request $request)
    {
        if(!($request->qty))
        {
            return response()->json(['success' => 0, 'message' => 'required qty']);
        }
        if(!($request->id))
        {
            return response()->json(['success' => 0, 'message' => 'required id']);
        }
        if(!($request->type_id))
        {
            return response()->json(['success' => 0, 'message' => 'required type_id']);
        }
        if($request->type_id==1){
            $soChild = So_child::where('id',$request->id)->first();

            if($soChild){
                if(!($soChild->qty >= $request->qty))
                {
                    return response()->json(['status' => 0, 'message' => 'qty exceeds.']);
                }
                $ProductInventory = ProductInventory::where('product_id',$soChild->product_id)->where('gender_id',$soChild->gender_id)->where('size_id',$soChild->size_id)->first();
                $ProductInventory->update([
                    'inventory' => $ProductInventory->inventory + $request->qty,
                    'used' => $ProductInventory->used - $request->qty,
                ]);
                $so = SO::with('users')->where('id',$soChild->so_id)->first();
                $old = $so->grand_total_amount;
                $so->update([
                    'total_qty' => $so->total_qty - $request->qty,
                    'total_amount' => $so->total_amount - ($request->qty * $soChild->wholesale_Price),
                    'grand_total_qty' => $so->grand_total_qty - $request->qty,
                    'grand_total_amount' => $so->grand_total_amount - ($request->qty * $soChild->wholesale_Price),
                ]);
                $new = $so->grand_total_amount;
                $newold = $old - $new;
                $pro = Product::where('id',$soChild->product_id)->first();
                // $gst = (($pro->gst*$soChild->wholesale_Price)/100)*$request->qty;
                $gst = ($soChild->wholesale_Price-($soChild->wholesale_Price*(100/(100+$pro->gst))))*$request->qty;
                $ledger_param = [
                    'so_id' => $so->id,
                    'created_by' => $so->created_by,
                    'shop_id' => $so->users->shop_id,
                    'cgst' => round(($gst/2),2),
                    'sgst' => round(($gst/2),2),
                    'debit' => $newold
                ];
                Ledger::create($ledger_param);
                if($request->qty == $soChild->qty)
                {
                    $soChild->delete();
                }else{
                    $soChild->qty = $soChild->qty - $request->qty;
                    $soChild->amount = $soChild->amount - ($request->qty * $soChild->wholesale_Price);
                    $soChild->save();
                }

                return response()->json(['success' => 1, 'message'=> 'So child data delete successfully!']);
            } else {
                return response()->json(['success' => 0, 'message'=> 'Data not found!']);
            }
        }
        if($request->type_id==2){
            $soOther = So_other::where('id',$request->id)->first();
            if($soOther){
                if(!($soOther->qty >= $request->qty))
                {
                    return response()->json(['status' => 0, 'message' => 'qty exceeds.']);
                }
                $so = SO::with('users')->where('id',$soOther->so_id)->first();
                $old = $so->grand_total_amount;
                $so->update([
                    'other_total_qty' => $so->other_total_qty - $request->qty,
                    'other_total_amount' => $so->other_total_amount - ($request->qty * ($soOther->price/$soOther->qty)),
                    'grand_total_qty' => $so->grand_total_qty - $request->qty,
                    'grand_total_amount' => $so->grand_total_amount - ($request->qty * ($soOther->price/$soOther->qty)),
                ]);
                $new = $so->grand_total_amount;
                $newold = $old - $new;
                // $gst = ((5*($soOther->price/$soOther->qty))/100)*$request->qty;
                $gst = ($soOther->price-($soOther->price*(100/(100+5))))*$request->qty;
                $ledger_param = [
                    'so_id' => $so->id,
                    'created_by' => $so->created_by,
                    'shop_id' => $so->users->shop_id,
                    'cgst' => round(($gst/2),2),
                    'sgst' => round(($gst/2),2),
                    'debit' => $newold
                ];
                Ledger::create($ledger_param);
                if($request->qty == $soOther->qty)
                {
                    $soOther->delete();
                }else{
                    $soOther->qty = $soOther->qty - $request->qty;
                    $soOther->amount = $soOther->amount - ($request->qty * ($soOther->price/$soOther->qty));
                    $soOther->save();
                }
                return response()->json(['success' => 1, 'message'=> 'So other data delete successfully!']);
            } else {
                return response()->json(['success' => 0, 'message'=> 'Data not found!']);
            }
        }
        if($request->type_id==3){
            $soRaw = SoRaw::where('id',$request->id)->first();
            if($soRaw){
                if(!($soRaw->qty >= $request->qty))
                {
                    return response()->json(['status' => 0, 'message' => 'qty exceeds.']);
                }
                $so = SO::with('users')->where('id',$soRaw->so_id)->first();
                $old = $so->grand_total_amount;
                $so->update([
                    'soraw_total_qty' => $so->soraw_total_qty - $request->qty,
                    'soraw_total_amount' => $so->soraw_total_amount - ($request->qty * ($soRaw->amount/$soRaw->qty)),
                    'grand_total_qty' => $so->grand_total_qty - $request->qty,
                    'grand_total_amount' => $so->grand_total_amount - ($request->qty * ($soRaw->amount/$soRaw->qty)),
                ]);
                $new = $so->grand_total_amount;
                $newold = $old - $new;
                $ledger_param = [
                    'so_id' => $so->id,
                    'created_by' => $so->created_by,
                    'shop_id' => $so->users->shop_id,
                    'debit' => $newold
                ];
                Ledger::create($ledger_param);
                if($request->qty == $soRaw->qty)
                {
                    $soRaw->delete();
                }else{
                    $soRaw->qty = $soRaw->qty - $request->qty;
                    $soRaw->amount = $soRaw->amount - ($request->qty * ($soRaw->amount/$soRaw->qty));
                    $soRaw->save();
                }
                return response()->json(['success' => 1, 'message'=> 'So raw data delete successfully!']);

            } else {
                return response()->json(['success' => 0, 'message'=> 'Data not found!']);
            }
        }

        if($request->type_id==4){
            $soOther = So_OtherTwo::where('id',$request->id)->first();
            if($soOther){
                if(!($soOther->qty >= $request->qty))
                {
                    return response()->json(['status' => 0, 'message' => 'qty exceeds.']);
                }
                $so = SO::with('users')->where('id',$soOther->so_id)->first();
                $old = $so->grand_total_amount;
                $so->update([
                    'other_total_qty_two' => $so->other_total_amount_two - $request->qty,
                    'other_total_amount_two' => $so->other_total_amount_two - ($request->qty * ($soOther->price/$soOther->qty)),
                    'grand_total_qty' => $so->grand_total_qty - $request->qty,
                    'grand_total_amount' => $so->grand_total_amount - ($request->qty * ($soOther->price/$soOther->qty)),
                ]);
                $new = $so->grand_total_amount;
                $newold = $old - $new;
                // $gst = ((18*($soOther->price/$soOther->qty))/100)*$request->qty;
                $gst = ($soOther->price-($soOther->price*(100/(100+18))))*$request->qty;
                $ledger_param = [
                    'so_id' => $so->id,
                    'created_by' => $so->created_by,
                    'shop_id' => $so->users->shop_id,
                    'cgst' => round(($gst/2),2),
                    'sgst' => round(($gst/2),2),
                    'debit' => $newold
                ];
                Ledger::create($ledger_param);
                if($request->qty == $soOther->qty)
                {
                    $soOther->delete();
                }else{
                    $soOther->qty = $soOther->qty - $request->qty;
                    $soOther->amount = $soOther->amount - ($request->qty * ($soOther->price/$soOther->qty));
                    $soOther->save();
                }
                return response()->json(['success' => 1, 'message'=> 'Delete successfully!']);
            } else {
                return response()->json(['success' => 0, 'message'=> 'Data not found!']);
            }
        }
        if($request->type_id==5){
            $soOther = So_OtherThree::where('id',$request->id)->first();
            if($soOther){
                if(!($soOther->qty >= $request->qty))
                {
                    return response()->json(['status' => 0, 'message' => 'qty exceeds.']);
                }
                $so = SO::with('users')->where('id',$soOther->so_id)->first();
                $old = $so->grand_total_amount;
                $so->update([
                    'other_total_qty_three' => $so->other_total_amount_three - $request->qty,
                    'other_total_amount_three' => $so->other_total_amount_three - ($request->qty * ($soOther->price/$soOther->qty)),
                    'grand_total_qty' => $so->grand_total_qty - $request->qty,
                    'grand_total_amount' => $so->grand_total_amount - ($request->qty * ($soOther->price/$soOther->qty)),
                ]);
                $new = $so->grand_total_amount;
                $newold = $old - $new;
                // $gst = ((12*($soOther->price/$soOther->qty))/100)*$request->qty;
                $gst = ($soOther->price-($soOther->price*(100/(100+12))))*$request->qty;
                $ledger_param = [
                    'so_id' => $so->id,
                    'created_by' => $so->created_by,
                    'shop_id' => $so->users->shop_id,
                    'cgst' => round(($gst/2),2),
                    'sgst' => round(($gst/2),2),
                    'debit' => $newold
                ];
                Ledger::create($ledger_param);
                if($request->qty == $soOther->qty)
                {
                    $soOther->delete();
                }else{
                    $soOther->qty = $soOther->qty - $request->qty;
                    $soOther->amount = $soOther->amount - ($request->qty * ($soOther->price/$soOther->qty));
                    $soOther->save();
                }
                return response()->json(['success' => 1, 'message'=> 'Delete successfully!']);
            } else {
                return response()->json(['success' => 0, 'message'=> 'Data not found!']);
            }
        }
    }

    public function deleteOrder(Request $request)
    {
        if(!($request->main_id))
        {
            return response()->json(['success' => 0, 'message' => 'required main_id']);
        }

        $so_main = SO::where('id',$request->main_id)->first();
        $soChild = So_child::where('so_id',$so_main->id)->get();

        if(count($soChild) > 0)
        {
            foreach ($soChild as $key => $value)
            {
                $ProductInventory = ProductInventory::where('product_id',$value->product_id)->where('gender_id',$value->gender_id)->where('size_id',$value->size_id)->first();
                $ProductInventory->update([
                    'inventory' => $ProductInventory->inventory + $value->qty,
                    'used' => $ProductInventory->used - $value->qty,
                ]);
                $so = SO::with('users')->where('id',$value->so_id)->first();
                $old = $so->grand_total_amount;
                $so->update([
                    'total_qty' => $so->total_qty - $value->qty,
                    'total_amount' => $so->total_amount - ($value->qty * $value->wholesale_Price),
                    'grand_total_qty' => $so->grand_total_qty - $value->qty,
                    'grand_total_amount' => $so->grand_total_amount - ($value->qty * $value->wholesale_Price),
                ]);
                $new = $so->grand_total_amount;
                $newold = $old - $new;
                $pro = Product::where('id',$value->product_id)->first();
                // $gst = (($pro->gst*$value->wholesale_Price)/100)*$value->qty;
                $gst = ($value->wholesale_Price-($value->wholesale_Price*(100/(100+$pro->gst))))*$value->qty;
                $ledger_param = [
                    'so_id' => $so->id,
                    'created_by' => $so->created_by,
                    'shop_id' => $so->users->shop_id,
                    'cgst' => round(($gst/2),2),
                    'sgst' => round(($gst/2),2),
                    'debit' => $newold
                ];
                Ledger::create($ledger_param);
                if($value->qty == $value->qty)
                {
                    $value->delete();
                }else{
                    $value->qty = $value->qty - $value->qty;
                    $value->amount = $value->amount - ($value->qty * $value->wholesale_Price);
                    $value->save();
                }
            }
        }

        $soOther = So_other::where('so_id',$so_main->id)->get();
        if(count($soOther) > 0)
        {
            foreach ($soOther as $key => $value)
            {
                $so = SO::with('users')->where('id',$value->so_id)->first();
                $old = $so->grand_total_amount;
                $so->update([
                    'other_total_qty' => $so->other_total_qty - $value->qty,
                    'other_total_amount' => $so->other_total_amount - ($value->qty * ($value->price/$value->qty)),
                    'grand_total_qty' => $so->grand_total_qty - $value->qty,
                    'grand_total_amount' => $so->grand_total_amount - ($value->qty * ($value->price/$value->qty)),
                ]);
                $new = $so->grand_total_amount;
                $newold = $old - $new;
                // $gst = ((5*($value->price/$value->qty))/100)*$value->qty;
                $gst = ($value->price-($value->price*(100/(100+5))))*$value->qty;
                $ledger_param = [
                    'so_id' => $so->id,
                    'created_by' => $so->created_by,
                    'shop_id' => $so->users->shop_id,
                    'cgst' => round(($gst/2),2),
                    'sgst' => round(($gst/2),2),
                    'debit' => $newold
                ];
                Ledger::create($ledger_param);
                if($value->qty == $value->qty)
                {
                    $value->delete();
                }else{
                    $value->qty = $value->qty - $value->qty;
                    $value->amount = $value->amount - ($value->qty * ($value->price/$value->qty));
                    $value->save();
                }
            }
        }
        $soRaw = SoRaw::where('so_id',$so_main->id)->get();
        if(count($soRaw) > 0)
        {
            foreach ($soRaw as $key => $value)
            {
                $so = SO::with('users')->where('id',$value->so_id)->first();
                $old = $so->grand_total_amount;
                $so->update([
                    'soraw_total_qty' => $so->soraw_total_qty - $value->qty,
                    'soraw_total_amount' => $so->soraw_total_amount - ($value->qty * ($value->amount/$value->qty)),
                    'grand_total_qty' => $so->grand_total_qty - $value->qty,
                    'grand_total_amount' => $so->grand_total_amount - ($value->qty * ($value->amount/$value->qty)),
                ]);
                $new = $so->grand_total_amount;
                $newold = $old - $new;
                $ledger_param = [
                    'so_id' => $so->id,
                    'created_by' => $so->created_by,
                    'shop_id' => $so->users->shop_id,
                    'debit' => $newold
                ];
                Ledger::create($ledger_param);
                if($value->qty == $value->qty)
                {
                    $value->delete();
                }else{
                    $value->qty = $value->qty - $value->qty;
                    $value->amount = $value->amount - ($value->qty * ($value->amount/$value->qty));
                    $value->save();
                }
            }
        }
        $soOther = So_OtherTwo::where('so_id',$so_main->id)->get();
        if(count($soOther) > 0)
        {
            foreach ($soOther as $key => $value)
            {
                $so = SO::with('users')->where('id',$value->so_id)->first();
                $old = $so->grand_total_amount;
                $so->update([
                    'other_total_qty_two' => $so->other_total_amount_two - $value->qty,
                    'other_total_amount_two' => $so->other_total_amount_two - ($value->qty * ($value->price/$value->qty)),
                    'grand_total_qty' => $so->grand_total_qty - $value->qty,
                    'grand_total_amount' => $so->grand_total_amount - ($value->qty * ($value->price/$value->qty)),
                ]);
                $new = $so->grand_total_amount;
                $newold = $old - $new;
                // $gst = ((18*($value->price/$value->qty))/100)*$value->qty;
                $gst = ($value->price-($value->price*(100/(100+18))))*$value->qty;
                $ledger_param = [
                    'so_id' => $so->id,
                    'created_by' => $so->created_by,
                    'shop_id' => $so->users->shop_id,
                    'cgst' => round(($gst/2),2),
                    'sgst' => round(($gst/2),2),
                    'debit' => $newold
                ];
                Ledger::create($ledger_param);
                if($value->qty == $value->qty)
                {
                    $value->delete();
                }else{
                    $value->qty = $value->qty - $value->qty;
                    $value->amount = $value->amount - ($value->qty * ($value->price/$value->qty));
                    $value->save();
                }
            }
        }
        $soOther = So_OtherThree::where('so_id',$so_main->id)->get();
        if(count($soOther) > 0)
        {
            foreach ($soOther as $key => $value)
            {
                $so = SO::with('users')->where('id',$value->so_id)->first();
                $old = $so->grand_total_amount;
                $so->update([
                    'other_total_qty_three' => $so->other_total_amount_three - $value->qty,
                    'other_total_amount_three' => $so->other_total_amount_three - ($value->qty * ($value->price/$value->qty)),
                    'grand_total_qty' => $so->grand_total_qty - $value->qty,
                    'grand_total_amount' => $so->grand_total_amount - ($value->qty * ($value->price/$value->qty)),
                ]);
                $new = $so->grand_total_amount;
                $newold = $old - $new;
                // $gst = ((12*($value->price/$value->qty))/100)*$value->qty;
                $gst = ($value->price-($value->price*(100/(100+12))))*$value->qty;
                $ledger_param = [
                    'so_id' => $so->id,
                    'created_by' => $so->created_by,
                    'shop_id' => $so->users->shop_id,
                    'cgst' => round(($gst/2),2),
                    'sgst' => round(($gst/2),2),
                    'debit' => $newold
                ];
                Ledger::create($ledger_param);
                if($value->qty == $value->qty)
                {
                    $value->delete();
                }else{
                    $value->qty = $value->qty - $value->qty;
                    $value->amount = $value->amount - ($value->qty * ($value->price/$value->qty));
                    $value->save();
                }
            }
        }
        // $so_main->delete();
        return response()->json(['success' => 1, 'message'=> 'Delete successfully!']);
    }
}