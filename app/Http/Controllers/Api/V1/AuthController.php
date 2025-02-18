<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use PDF;

class AuthController extends Controller
{
	public function login(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'email' => 'required|email|',
			'password' => 'required',
		]);
		$credentials = $request->only('email', 'password');

		$user = User::where('email',$request->email)->with('shop')->first();
		if($user){
			$isactive = User::where('email',$request->email)->with('shop')->where('status',1)->first();
			if($isactive){
				try {
					if (! $token = JWTAuth::attempt($credentials)) {
						return response()->json(['success' => 0, 'error' => 'We cant find an account with this credentials. Please make sure you entered the right information and you have verified your email address.'], 200);
					}
				} catch (JWTException $e) {
					return response()->json(['success' => 0, 'error' => 'Failed to login, please try again.'], 200);
				}

				return response()->json(['success' => 1, 'data'=> [ 'token' => $token, 'data' => $user ]], 200);
			} else {
				return response()->json([
					'success' => 0, 
					'error' => 'User is Inactive.'
				], 200);
			}

		} else {
			return response()->json([
				'success' => 0, 
				'error' => 'No user found.'
			], 200);
		}

	}
	public function getnewdata(Request $request)
	{
		if(!($request->shop_id))
		{
			return response()->json(['success' => 0, 'message' => 'Required shop_id']);
		}
		if(!($request->from_date))
		{
			return response()->json(['success' => 0, 'message' => 'Required from_date']);
		}
		if(!($request->to_date))
		{
			return response()->json(['success' => 0, 'message' => 'Required to_date']);
		}
		if(!($request->gstin))
		{
			return response()->json(['success' => 0, 'message' => 'Required gstin']);
		}
		if(!($request->discount))
		{
			return response()->json(['success' => 0, 'message' => 'Required discount']);
		}
		$data = Ledger::orderBy('so_id');
		if($request->shop_id)
		{
			$data = $data->where('shop_id',$request->shop_id);
		}
		if($request->from_date && $request->to_date)
		{
			$data = $data->whereDate('created_at','>=',$request->from_date)->whereDate('created_at','<=',$request->to_date);
		}
		$data = $data->select('so_id')->get();
		// $datas['html'] = '<table class="table table-striped- table-bordered table-hover table-checkable datatable" style="width:100%"><thead><tr><th>Order</th><th>Item</th><th>Quantity</th><th>Amount</th><th>CGST</th><th>SGST</th><th>CGST Amount</th><th>SGST Amount</th><th>Total</th></tr></thead><tbody>';
		$main_array = [];
		$t_main_total = 0;
		$t_gst = 0;
		$total = 0;
		foreach ($data as $key => $value) {
			$so_child = So_child::where('so_id',$value->so_id)->get();
			$so_child1 = So_child::where('so_id',$value->so_id)->get()->toArray();
			if($so_child1)
			{
				foreach ($so_child as $keys => $values) {
					$values->amount = $values->amount - (($request->discount *$values->amount)/100);
					$product = Product::where('id',$values->product_id)->first();
					if($product->gst == $request->gstin)
					{
						$gst = $values->amount-($values->amount*(100/(100+$product->gst)));
						$main_amount = $values->amount - $gst;
						$t_main_total += round($main_amount,2);
						$t_gst += round($gst/2,2);
						$total += round($values->amount,2);
						$param = [
							'order' => SO::where('id',$value->so_id)->value('order_no'),
							'name' => $product->name,
							'qty' => $values->qty,
							'amount' => round($main_amount,2),
							'cgst' => round(($product->gst)/2,2),
							'cgst_amount' => round($gst/2,2),
							'main_amount' => $values->amount
						];
						array_push($main_array,$param);
						// $datas['html'] .= '<tr><td>'.$value->so_id.'</td><td>'.$product->name.'</td><td>'.$values->qty.'</td><td>'.round($main_amount,2).'</td><td>'.round(($product->gst)/2,2).'</td><td>'.round(($product->gst)/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.$values->amount.'</td></tr>';
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
						$values->amount = $values->amount - (($request->discount *$values->amount)/100);
						$gst = $values->amount-($values->amount*(100/(100+18)));
						$main_amount = $values->amount - $gst;
						$t_main_total += round($main_amount,2);
						$t_gst += round($gst/2,2);
						$total += round($values->amount,2);
						$param = [
							'order' => SO::where('id',$value->so_id)->value('order_no'),
							'name' => $values->name,
							'qty' => $values->qty,
							'amount' => round($main_amount,2),
							'cgst' => round((18)/2,2),
							'cgst_amount' => round($gst/2,2),
							'main_amount' => $values->amount
						];
						array_push($main_array,$param);
						// $datas['html'] .= '<tr><td>'.$value->so_id.'</td><td>'.$values->name.'</td><td>'.$values->qty.'</td><td>'.round($main_amount,2).'</td><td>'.round((18)/2,2).'</td><td>'.round((18)/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.$values->amount.'</td></tr>';
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
						$values->amount = $values->amount - (($request->discount *$values->amount)/100);
						$gst = $values->amount-($values->amount*(100/(100+5)));
						$main_amount = $values->amount - $gst;
						$t_main_total += round($main_amount,2);
						$t_gst += round($gst/2,2);
						$total += round($values->amount,2);
						$param = [
							'order' => SO::where('id',$value->so_id)->value('order_no'),
							'name' => $values->name,
							'qty' => $values->qty,
							'amount' => round($main_amount,2),
							'cgst' => round((5)/2,2),
							'cgst_amount' => round($gst/2,2),
							'main_amount' => $values->amount
						];
						array_push($main_array,$param);
						// $datas['html'] .= '<tr><td>'.$value->so_id.'</td><td>'.$values->name.'</td><td>'.$values->qty.'</td><td>'.round($main_amount,2).'</td><td>'.round((5)/2,2).'</td><td>'.round((5)/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.$values->amount.'</td></tr>';
					}
				}
			}
		}
		// $datas['html'] .= '<tr><td colspan="3">Total</td><td>'.$t_main_total.'</td><td></td><td></td><td>'.round($t_gst,2).'</td><td>'.round($t_gst,2).'</td><td>'.$total.'</td></tr></tbody></table>';

		// echo $html;
		// $pdf = PDF::setOptions(['logOutputFile' => storage_path('logs/log.htm'), 'tempDir' => storage_path('logs/'), 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('product.ledgerpdf',$datas)->setPaper('A4', 'landscape');

		// $pdf->save(now().'.pdf');

		return response()->json(['success' => 1, 't_main_total' => round($t_main_total,2), 't_gst' => round($t_gst,2), 'total' => round($total,2), 'pdf' => now().'.pdf' ,'data' => $main_array]);
	}
	public function getnewdatadownload(Request $request)
	{
		if(!($request->shop_id))
		{
			return response()->json(['success' => 0, 'message' => 'Required shop_id']);
		}
		if(!($request->from_date))
		{
			return response()->json(['success' => 0, 'message' => 'Required from_date']);
		}
		if(!($request->to_date))
		{
			return response()->json(['success' => 0, 'message' => 'Required to_date']);
		}
		if(!($request->gstin))
		{
			return response()->json(['success' => 0, 'message' => 'Required gstin']);
		}
		if(!($request->discount))
		{
			return response()->json(['success' => 0, 'message' => 'Required discount']);
		}
		$data = Ledger::orderBy('so_id');
		if($request->shop_id)
		{
			$data = $data->where('shop_id',$request->shop_id);
		}
		if($request->from_date && $request->to_date)
		{
			$data = $data->whereDate('created_at','>=',$request->from_date)->whereDate('created_at','<=',$request->to_date);
		}
		$data = $data->select('so_id')->get();
		$datas['html'] = '<table class="table table-striped- table-bordered table-hover table-checkable datatable" style="width:100%"><thead><tr><th>Order</th><th>Item</th><th>Quantity</th><th>Amount</th><th>CGST</th><th>SGST</th><th>CGST Amount</th><th>SGST Amount</th><th>Total</th></tr></thead><tbody>';
		$main_array = [];
		$t_main_total = 0;
		$t_gst = 0;
		$total = 0;
		foreach ($data as $key => $value) {
			$so_child = So_child::where('so_id',$value->so_id)->get();
			$so_child1 = So_child::where('so_id',$value->so_id)->get()->toArray();
			if($so_child1)
			{
				foreach ($so_child as $keys => $values) {
					$values->amount = $values->amount - (($request->discount *$values->amount)/100);
					$product = Product::where('id',$values->product_id)->first();
					if($product->gst == $request->gstin)
					{
						$gst = $values->amount-($values->amount*(100/(100+$product->gst)));
						$main_amount = $values->amount - $gst;
						$t_main_total += round($main_amount,2);
						$t_gst += round($gst/2,2);
						$total += round($values->amount,2);
						// $param = [
						// 	'order' => $value->so_id,
						// 	'name' => $product->name,
						// 	'qty' => $values->qty,
						// 	'amount' => round($main_amount,2),
						// 	'cgst' => round(($product->gst)/2,2),
						// 	'cgst_amount' => round($gst/2,2),
						// 	'main_amount' => $values->amount
						// ];
						// array_push($main_array,$param);
						$datas['html'] .= '<tr><td>'.$value->so_id.'</td><td>'.$product->name.'</td><td>'.$values->qty.'</td><td>'.round($main_amount,2).'</td><td>'.round(($product->gst)/2,2).'</td><td>'.round(($product->gst)/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.$values->amount.'</td></tr>';
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
						$values->amount = $values->amount - (($request->discount *$values->amount)/100);
						$gst = $values->amount-($values->amount*(100/(100+18)));
						$main_amount = $values->amount - $gst;
						$t_main_total += round($main_amount,2);
						$t_gst += round($gst/2,2);
						$total += round($values->amount,2);
						// $param = [
						// 	'order' => $value->so_id,
						// 	'name' => $values->name,
						// 	'qty' => $values->qty,
						// 	'amount' => round($main_amount,2),
						// 	'cgst' => round((18)/2,2),
						// 	'cgst_amount' => round($gst/2,2),
						// 	'main_amount' => $values->amount
						// ];
						// array_push($main_array,$param);
						$datas['html'] .= '<tr><td>'.$value->so_id.'</td><td>'.$values->name.'</td><td>'.$values->qty.'</td><td>'.round($main_amount,2).'</td><td>'.round((18)/2,2).'</td><td>'.round((18)/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.$values->amount.'</td></tr>';
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
						$values->amount = $values->amount - (($request->discount *$values->amount)/100);
						$gst = $values->amount-($values->amount*(100/(100+5)));
						$main_amount = $values->amount - $gst;
						$t_main_total += round($main_amount,2);
						$t_gst += round($gst/2,2);
						$total += round($values->amount,2);
						// $param = [
						// 	'order' => $value->so_id,
						// 	'name' => $values->name,
						// 	'qty' => $values->qty,
						// 	'amount' => round($main_amount,2),
						// 	'cgst' => round((5)/2,2),
						// 	'cgst_amount' => round($gst/2,2),
						// 	'main_amount' => $values->amount
						// ];
						// array_push($main_array,$param);
						$datas['html'] .= '<tr><td>'.$value->so_id.'</td><td>'.$values->name.'</td><td>'.$values->qty.'</td><td>'.round($main_amount,2).'</td><td>'.round((5)/2,2).'</td><td>'.round((5)/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.round($gst/2,2).'</td><td>'.$values->amount.'</td></tr>';
					}
				}
			}
		}
		$datas['html'] .= '<tr><td colspan="3">Total</td><td>'.$t_main_total.'</td><td></td><td></td><td>'.round($t_gst,2).'</td><td>'.round($t_gst,2).'</td><td>'.$total.'</td></tr></tbody></table>';

		// echo $html;
		$pdf = PDF::setOptions(['logOutputFile' => storage_path('logs/log.htm'), 'tempDir' => storage_path('logs/'), 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('product.ledgerpdf',$datas)->setPaper('A4', 'landscape');

		$pdf->save('ledgers/'.now().'.pdf');

		return response()->json(['success' => 1, 'pdf' => now().'.pdf']);
	}
	public function users()
	{
		$data = User::with('shop')->get();

		return response()->json(['success' => 1, 'data' => $data]);
	}
	public function shop()
	{
		$data = Shop::get();

		return response()->json(['success' => 1, 'data' => $data]);
	}
	public function createusers(Request $request)
	{
		if(!($request->name))
		{
			return response()->json(['success' => 0, 'message' => 'Required name']);
		}
		if(!($request->email))
		{
			return response()->json(['success' => 0, 'message' => 'Required email']);
		}
		if(!($request->password))
		{
			return response()->json(['success' => 0, 'message' => 'Required password']);
		}
		if(!($request->role))
		{
			return response()->json(['success' => 0, 'message' => 'Required role']);
		}
		if($request->role == 3)
		{
			if(!($request->shop_id))
			{
				return response()->json(['success' => 0, 'message' => 'Required shop_id']);
			}
		}

		$user = new User();
		$user->name = $request->name;
		$user->email = $request->email;
		$user->role = $request->role;
		if($request->role == 3)
		{
			$user->shop_id = $request->shop_id;
		}
		$user->password = bcrypt($request->password); 
		$user->show_password = $request->password;
		$user->status = $request->status ?? 0;
		$user->save();

		if(isset($user))
		{
			return response()->json(['success' => 1, 'message' => 'User Added successfully']);
		}else{
			return response()->json(['success' => 0, 'message' => 'Something went wrong']);
		}
	}

	public function updateusers(Request $request)
	{
		if(!($request->id))
		{
			return response()->json(['success' => 0, 'message' => 'Required id']);
		}
		if(!($request->name))
		{
			return response()->json(['success' => 0, 'message' => 'Required name']);
		}
		if(!($request->email))
		{
			return response()->json(['success' => 0, 'message' => 'Required email']);
		}

		if(!($request->role))
		{
			return response()->json(['success' => 0, 'message' => 'Required role']);
		}
		if($request->role == 3)
		{
			if(!($request->shop_id))
			{
				return response()->json(['success' => 0, 'message' => 'Required shop_id']);
			}
		}
		$param = $request->all();
		unset($param['id']);
		$data = User::where('id',$request->id)->first();

		if($request->password)
		{
			$param['show_password'] = $param['password'];
			$param['password'] = bcrypt($param['password']);
		}
		$data->update($param);
		$data->save();

		return response()->json(['success' => 1, 'message' => 'user updated successfully']);
	}

	public function home()
	{
		if(JWTAuth::user()->role == 3)
		{
			return response()->json(['success' => 1, 'message' => 'You are not admin']);
		}
		$data['all_orders'] = So::count();
		$data['all_income'] = So::sum('grand_total_amount');
		$data['todays_orders'] = So::whereDate('created_at', '=', date('Y-m-d'))->count();
		$data['todays_income'] = So::whereDate('created_at', '=', date('Y-m-d'))->sum('grand_total_amount');
		$data['week_orders'] = So::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
		$data['week_income'] = So::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('grand_total_amount');
		$data['month_orders'] = So::whereMonth('created_at', '=', date('m'))->count();
		$data['month_income'] = So::whereMonth('created_at', '=', date('m'))->sum('grand_total_amount');
		$data['year_orders'] = So::whereYear('created_at', '=', date('Y'))->count();
		$data['year_income'] = So::whereYear('created_at', '=', date('Y'))->sum('grand_total_amount');

		$shop = Shop::get();
		foreach ($shop as $key => $value) {
			$ids = $value->id;
			$param[] = [
				'name' => $value->name,
				'all_orders' => So::whereHas('users',function($q) use($ids){
					$q->where('shop_id',$ids);
				})->count(),
				'all_income' => So::whereHas('users',function($q) use($ids){
					$q->where('shop_id',$ids);
				})->sum('grand_total_amount'),
				'todays_orders' => So::whereDate('created_at', '=', date('Y-m-d'))->whereHas('users',function($q) use($ids){
					$q->where('shop_id',$ids);
				})->count(),
				'todays_income' => So::whereDate('created_at', '=', date('Y-m-d'))->whereHas('users',function($q) use($ids){
					$q->where('shop_id',$ids);
				})->sum('grand_total_amount'),
				'week_orders' => So::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->whereHas('users',function($q) use($ids){
					$q->where('shop_id',$ids);
				})->count(),
				'week_income' => So::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->whereHas('users',function($q) use($ids){
					$q->where('shop_id',$ids);
				})->sum('grand_total_amount'),
				'month_orders' => So::whereMonth('created_at', '=', date('m'))->whereHas('users',function($q) use($ids){
					$q->where('shop_id',$ids);
				})->count(),
				'month_income' => So::whereMonth('created_at', '=', date('m'))->whereHas('users',function($q) use($ids){
					$q->where('shop_id',$ids);
				})->sum('grand_total_amount'),
				'year_orders' => So::whereYear('created_at', '=', date('Y'))->whereHas('users',function($q) use($ids){
					$q->where('shop_id',$ids);
				})->count(),
				'year_income' => So::whereYear('created_at', '=', date('Y'))->whereHas('users',function($q) use($ids){
					$q->where('shop_id',$ids);
				})->sum('grand_total_amount'),
			];
		}
		$data['shop'] = $param;
		return response()->json(['success' => 1, 'data' => $data]);
	}

	public function newhome(Request $request)
	{
		if(!($request->date))
		{
			return response()->json(['success' => 0, 'message' => 'Required date']);
		}
		if(!($request->shop_id))
		{
			return response()->json(['success' => 0, 'message' => 'Required shop_id']);
		}
		$so = So::leftJoin('user as uu','uu.id','so.created_by')->where('uu.shop_id',$request->shop_id)->whereDate('so.created_at',$request->date)->sum('so.grand_total_amount');

		return response()->json(['success' => 1, 'data' => $so]);

	}
}