<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class ProductSize extends Model
{
    protected $table = 'product_size';

    protected $fillable = ['product_id','size_id'];
    public $timestamps = false;

    public function sizes()
    {
        return $this->belongsToMany('App\Product');
    }
}



 