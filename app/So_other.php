<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class So_other extends Model
{
    protected $table = 'so_other';
    protected $primarykey = 'id';
    protected $guarded = ['id'];

    protected $appends = ['gst_per','gst_amount','amount_before_gst','total_amount_before_gst'];

    public function otherproduct()
    {
        return $this->belongsTo(Otherproduct::class, 'other_product_id','id');
    }
    public function getGstPerAttribute()
    {
        return 5;
    }
    public function getGstAmountAttribute()
    {
        return $this->price*(100/(100+5))*$this->qty;
    }
    public function getAmountBeforeGstAttribute()
    {
        return $this->price-($this->price*(100/(100+5)));
    }
    public function getTotalAmountBeforeGstAttribute()
    {
        return ($this->price-($this->price*(100/(100+5))))*$this->qty;
    }
}
