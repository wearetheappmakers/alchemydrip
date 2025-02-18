<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\ProductPrice;
use App\So;
use App\Shop;
use App\B2b;
use App\User;
use Carbon\Carbon;
use DB;
use Datatables;

class HomeController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function index()
	{
		return view('main');
	}

	public function deleteMultiple(Request $request)
	{

		$table_name = $request->get('table_name');
		$id_array = explode(',', $request->get('id'));

		try {

			DB::table($table_name)->whereIn('id', $id_array)->delete();

			$res['status'] = 'Success';
			$res['message'] = 'Deleted successfully';

		} catch (\Exception $ex) {
			$res['status'] = 'Error';
			$res['message'] = $ex->getMessage();
		}
		return response()->json($res); 
	}


	public function changeMultipleStatus(Request $request)
	{
		$table_name = $request->get('table_name');
		$param = $request->get('param');
		$id_array = explode(',', $request->get('id'));


		try {

			if($table_name == 'product' && $param == 1)
			{

				foreach ($id_array as $ids => $id) {

					$product_genders_f = DB::table('product_gender')->where('product_id',$id)->first();
					$product_sizes_f = DB::table('product_size')->where('product_id',$id)->first();
					$product_genders = DB::table('product_gender')->where('product_id',$id)->get();
					$product_sizes = DB::table('product_size')->where('product_id',$id)->get();

					if(!(isset($product_sizes_f) && isset($product_genders_f)))
					{
						$res['status'] = 'Errors';
						$res['message'] = 'Incomplete product data!';
						return response()->json($res);
					}elseif (!(isset($product_genders_f))) {
						$res['status'] = 'Errors';
						$res['message'] = 'Incomplete product Gender!';
						return response()->json($res);
					}elseif (!(isset($product_sizes_f))) {
						$res['status'] = 'Errors';
						$res['message'] = 'Incomplete product size!';
						return response()->json($res);
					}

					foreach ($product_genders as $key => $value) {

						foreach ($product_sizes as $keys => $values) {


							$product_prices = DB::table('product_price')->where([['product_id',$id],['size_id',$values->size_id],['gender_id',$value->gender_id],['price','!=',NULL],['wholesale_price','!=',NULL]])->first();
							$product_inventories = DB::table('product_inventory')->where([['product_id',$id],['size_id',$values->size_id],['gender_id',$value->gender_id],['inventory','!=',NULL]])->first();

							if(!(isset($product_prices) && (isset($product_inventories) )))
							{
								$res['status'] = 'Errors';
								$res['message'] = 'Incomplete product prices and inventorys!';
								return response()->json($res);
							}elseif(!(isset($product_inventories)))
							{
								$res['status'] = 'Errors';
								$res['message'] = 'Incomplete product inventorys!';
								return response()->json($res);
							}elseif(!(isset($product_prices)))
							{
								$res['status'] = 'Errors';
								$res['message'] = 'Incomplete product prices!';
								return response()->json($res);
							}
						}

					}
				}
			}
			if ($param == 0) {
				foreach ($id_array as $id) {
					DB::table($table_name)->where('id', $id)
					->update([
						$request->field => 0,
					]);
				}
			} elseif ($param == 1) {
				foreach ($id_array as $id) {
					DB::table($table_name)->where('id', $id)
					->update([
						$request->field => 1,
					]);
				}
			}

			$res['status'] = 'Success';
			$res['message'] = 'Status Change successfully';

		} catch (\Exception $ex) {
			$res['status'] = 'Error';
			$res['message'] = 'Something went wrong.';
		}

		return response()->json($res);
	}




	public function dashboard()
	{
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
		return view('home')->with($data);
	}

	public function reportemployee(Request $request)
	{
		$data['shop'] = Shop::get();

		return view('employeereport')->with($data);
	}
	public function reportemployeegetdata(Request $request)
	{
		$user = User::where('shop_id',$request->shop_id)->where('status',1)->get();

		$html = '<table class="table table-striped- table-bordered table-hover table-checkable">
		<thead>
		<tr>
		<th>#</th>
		<th>Employee</th>
		<th>Total Bill</th>
		<th>Total Amount</th>
		</tr>
		</thead><tbody>';
		foreach ($user as $key => $value)
		{
			$total_bill = So::where('created_by',$value->id)->whereDate('created_at','>=',$request->from_date)->whereDate('created_at','<=',$request->to_date)->count();
			$total_amount = So::where('created_by',$value->id)->whereDate('created_at','>=',$request->from_date)->whereDate('created_at','<=',$request->to_date)->sum('grand_total_amount');

			$html .= '<tr>
			<th>'. ($key+1) .'</th>
			<th>'. $value->name .'</th>
			<th>'. $total_bill .'</th>
			<th>'. $total_amount .'</th>
			</tr>';
		}
		$html .= '</tbody></table>';

		return response()->json(['status' => 'success', 'html' => $html]);
	}
}
