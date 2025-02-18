<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    protected $table = 'product_price';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
    public function genders()
    {
        return $this->belongsTo('App\Gender', 'gender_id', 'id');
    }

    public function sizes()
    {
        return $this->belongsTo('App\Size', 'size_id', 'id');
    }

   
}
