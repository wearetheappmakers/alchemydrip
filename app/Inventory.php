<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    
    protected $table = 'inventory';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public function product()
    {
     return $this->belongsTo(Product::class,'product_id','id');
    }

}
