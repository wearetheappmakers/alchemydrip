<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\ProductInventory;
use App\ProductPrice;
use App\School;
use App\ProductSize;

class Product extends Model
{
    protected $table = 'product';
    protected $primarykey = 'id';
    protected $guarded = ['id'];

    public function school()
    {
    	return $this->belongsTo(School::class, 'school_id','id');
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class,'product_size');
    }

    public function genders()
    {
        return $this->belongsToMany(Gender::class, 'product_gender');
    }

    public function product_inventorys()
    {
        return $this->hasMany('App\ProductInventory');
    }

    public function product_prices()
    {
        return $this->hasMany('App\ProductPrice');
    }
}

