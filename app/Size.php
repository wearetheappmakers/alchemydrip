<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $table = 'size';
    protected $primarykey = 'id';
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsToMany(Product::class,'product_size');
    }
}
