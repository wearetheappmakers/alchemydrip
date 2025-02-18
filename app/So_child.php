<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class So_child extends Model
{
    protected $table = 'so_child';
    protected $primarykey = 'id';
    protected $guarded = ['id'];

    protected $appends = ['gst_per','gst_amount','amount_before_gst','total_amount_before_gst'];

    public function schools()
    {
        return $this->belongsTo(School::class, 'school_id','id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id','id');
    }

     public function sizes()
    {
        return $this->belongsTo(Size::class,'size_id','id');
    }

     public function genders()
    {
        return $this->belongsTo(Gender::class,'gender_id','id');
    }

    public function prices()
    {
        return $this->belongsTo(ProductPrice::class,'price','id');
    }

    public function wholesale_Prices()
    {
        return $this->belongsTo(ProductPrice::class,'wholesale_Price','id');
    }
    public function getGstPerAttribute()
    {
        return $this->product->gst;
    }
    public function getGstAmountAttribute()
    {
        return $this->wholesale_Price*(100/(100+$this->product->gst))*$this->qty;
    }
    public function getAmountBeforeGstAttribute()
    {
        return $this->wholesale_Price-($this->wholesale_Price*(100/(100+$this->product->gst)));
    }
    public function getTotalAmountBeforeGstAttribute()
    {
        return ($this->wholesale_Price-($this->wholesale_Price*(100/(100+$this->product->gst))))*$this->qty;
    }
}
