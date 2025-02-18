<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class So_OtherThree extends Model
{
    protected $table = 'so_other_three';
    protected $primarykey = 'id';
    protected $guarded = ['id'];

    protected $appends = ['gst_per','gst_amount','amount_before_gst','total_amount_before_gst'];

    public function otherproductthree()
    {
        return $this->belongsTo(OtherProductThree::class, 'other_product_id','id');
    }
    public function getGstPerAttribute()
    {
        return 12;
    }
    public function getGstAmountAttribute()
    {
        return $this->price*(100/(100+12))*$this->qty;
    }
    public function getAmountBeforeGstAttribute()
    {
        return $this->price-($this->price*(100/(100+12)));
    }
    public function getTotalAmountBeforeGstAttribute()
    {
        return ($this->price-($this->price*(100/(100+12))))*$this->qty;
    }
}
