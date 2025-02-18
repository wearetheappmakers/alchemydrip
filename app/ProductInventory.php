<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductInventory extends Model
{
    protected $table = 'product_inventory';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

     public function Products()
    {
        return $this->belongsTo('App\Product', 'product_id', 'id');
    }
      
    public function Genders()
    {
        return $this->belongsTo('App\Gender', 'gender_id', 'id');
    }

    public function sizes()
    {
        return $this->belongsTo('App\Size', 'size_id', 'id');
    }
}
