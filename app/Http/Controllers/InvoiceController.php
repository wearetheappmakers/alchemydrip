<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use App\InvoiceChild;
use App\Product;
use App\B2b;
use App\child;
use App\B2b_child;
use App\Setting;
use PDF;
use DB;

class InvoiceController extends Controller
{
    public function pdf(Request $request)
    {
        $data['datas'] = B2b::where('id',$request->id)->first();
        $setting = Setting::where('id',1)->first();
        $inv = B2b::where('id',$request->id)->first();

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

        $child = B2b_child::where('b2b_id',$request->id)->get();
        $invs = B2b::where('id',$request->id)->first();
        $count = 0;
        $number = 1;
        foreach ($child as $key=>$value) 
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

        $data['data'].='<tr><td style="float:right;border-collapse:collapse !important;border: none !important"></td><th colspan="5" style="text-align:right;border-collapse:collapse !important;border: none !important">Total(INR)</th><th style="border-collapse:collapse !important;border: none !important;text-align:right">'.$inv->total_amount.'</th>
        </th></tr><br><br>';

        $data['data'].='</table><b>Total (in words)&nbsp:</b>&nbsp<font size="14px">'.numberTowords($count).' </font><br><br><br>
        <p style="text-align:left;color:#7545BC">Additional Notes</p>
        <span style="text-align:left;">The final amount is inclusive of GST</span><br><br><br>
        <p style="text-align:Center;">For any enquiry, reach out via email at <b>'.$setting->email.'</b>, call on <b>'.$setting->contact.'</b></p>
        <p style=" position: absolute; bottom: 0; left: 0; width: 100%; text-align: center;">This is an electronically generated document, no signature is required.</p>';

        $pdf = PDF::setOptions(['logOutputFile' => storage_path('logs/log.htm'), 'tempDir' => storage_path('logs/'), 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf', $data)->setPaper('A4', 'portrait');

        // $certificate_name = $request->id.'-DRS.pdf';
        // $pdf->save(public_path('b2b/' . $certificate_name));

        return $pdf->download('invoice.pdf');
    }
}