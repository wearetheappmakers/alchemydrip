<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductGender extends Model
{
    protected $table = 'product_gender';

    protected $fillable = ['product_id','gender_id'];
    public $timestamps = false;

}
